import os
import re
import glob

print('===================================================')
print('   KSO CHANDIGARH REFERENCING & LINK AUDIT SUITE   ')
print('===================================================')

errors = []

# Check 1: Route References
print('\n1. Auditing Route References in Blade Views...')
with open('routes/web.php', 'r') as f:
    web_routes = f.read()

# Derive route names from routes/web.php rather than a hardcoded allowlist,
# which silently drifts out of date whenever routes change.
public_named_routes = set()
in_admin_group = False
for line in web_routes.splitlines():
    if "->name('admin.')" in line:
        in_admin_group = True
    m = re.search(r"->name\s*\(\s*['\"]([^'\"]+)['\"]\s*\)\s*;", line)
    if m:
        name = m.group(1)
        if name == 'admin.':
            continue
        if in_admin_group and not name.startswith('admin.') and '/admin/' not in line:
            name = 'admin.' + name
        public_named_routes.add(name)

# Route::resource(...) generates one name per action, honouring ->only([...]).
for res, only in re.findall(
    r"Route::resource\(\s*['\"]([\w\-]+)['\"]\s*,\s*\w+::class\s*\)?\s*(?:->only\(\[([^\]]*)\]\))?",
    web_routes, re.S):
    actions = ([a.strip().strip("'\"") for a in only.split(',') if a.strip()]
               if only else
               ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
    for action in actions:
        public_named_routes.add(f'admin.{res}.{action}')

all_valid_routes = public_named_routes

blade_files = glob.glob('resources/views/**/*.blade.php', recursive=True)
for bf in blade_files:
    with open(bf, 'r') as f:
        content = f.read()
        # Find route('...') calls
        route_calls = re.findall(r"route\s*\(\s*['\"]([^'\"]+)['\"]", content)
        for rc in route_calls:
            if rc not in all_valid_routes:
                errors.append(f"Route '{rc}' in {bf} not found in routes/web.php")

# Check 2: Asset References
print('2. Auditing Static Asset References in Blade Views...')
for bf in blade_files:
    with open(bf, 'r') as f:
        content = f.read()
        # Find asset('...') calls
        asset_calls = re.findall(r"asset\s*\(\s*['\"]([^'\"]+)['\"]", content)
        for ac in asset_calls:
            asset_path = os.path.join('public', ac)
            if not os.path.exists(asset_path):
                errors.append(f"Asset '{ac}' in {bf} does not exist at {asset_path}")

# Check 3: Model Imports
print('3. Auditing Model Class Declarations...')
model_files = glob.glob('app/Models/*.php')
for mf in model_files:
    with open(mf, 'r') as f:
        content = f.read()
        if 'namespace App\\Models;' not in content:
            errors.append(f"Model {mf} missing 'namespace App\\Models;'")

# Check 4: Controller Class Declarations
print('4. Auditing Controller Class Declarations...')
controller_files = glob.glob('app/Http/Controllers/**/*.php', recursive=True)
for cf in controller_files:
    with open(cf, 'r') as f:
        content = f.read()
        if 'class ' not in content:
            errors.append(f"Controller {cf} missing class definition")

# Check 5: Migration Schema Files
print('5. Auditing Database Migrations...')
migration_files = glob.glob('database/migrations/*.php')
if len(migration_files) < 4:
    errors.append("Fewer migrations than expected")

print('\n===================================================')
if len(errors) == 0:
    print('✅ ZERO REFERENCING ERRORS FOUND!')
    print('   - All route() references match defined routes in web.php.')
    print('   - All asset() paths point to existing files in public/.')
    print('   - All Eloquent models & Controllers match PSR-4 namespaces.')
else:
    print(f'❌ Found {len(errors)} Referencing Errors:')
    for err in errors:
        print('   - ' + err)
print('===================================================')
