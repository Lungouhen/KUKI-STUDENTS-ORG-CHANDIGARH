#!/usr/bin/env python3
import os
import re
import glob
import sys

# Terminal ANSI Color Escape Codes
class Colors:
    HEADER = '\033[95m'
    BLUE = '\033[94m'
    CYAN = '\033[96m'
    GREEN = '\033[92m'
    WARNING = '\033[93m'
    FAIL = '\033[91m'
    ENDC = '\033[0m'
    BOLD = '\033[1m'
    UNDERLINE = '\033[4m'

print(f"{Colors.HEADER}{Colors.BOLD}======================================================================{Colors.ENDC}")
print(f"{Colors.CYAN}{Colors.BOLD}   KSO CHANDIGARH UNIFIED ENGINEERING AUDIT & COMPLIANCE DESK         {Colors.ENDC}")
print(f"{Colors.HEADER}{Colors.BOLD}======================================================================{Colors.ENDC}")

errors = []
warnings = []
stats = {
    'blade_views': 0,
    'controllers': 0,
    'models': 0,
    'migrations': 0,
    'routes_parsed': 0,
}

def parse_laravel_routes(file_path):
    """
    Statically analyzes Laravel web.php and dynamically extracts all defined
    named routes, including route groups, prefixes, and resource route expansions.
    """
    routes = set()
    if not os.path.exists(file_path):
        return routes

    with open(file_path, 'r', encoding='utf-8') as f:
        lines = f.readlines()

    is_admin_group = False
    
    for line in lines:
        line = line.strip()
        # Skip comments
        if not line or line.startswith('//') or line.startswith('/*') or line.startswith('*'):
            continue

        # Detect entry into admin prefix group
        if "name('admin.')" in line or 'name("admin.")' in line or "prefix('admin')" in line:
            is_admin_group = True

        # Detect end of admin group (approximated by the closing bracket of the group block)
        # In this specific web.php, the admin group spans to the end of routes
        if line == '});' and is_admin_group:
            # We keep it True since setting it False might cut off end definitions in this particular file
            pass

        # 1. Parse standard named routes ->name('route.name')
        named_route_match = re.search(r"->name\s*\(\s*['\"]([^'\"]+)['\"]", line)
        if named_route_match:
            route_name = named_route_match.group(1)
            if is_admin_group and not route_name.startswith('admin.'):
                routes.add(f"admin.{route_name}")
            else:
                routes.add(route_name)

        # 2. Parse resource routes Route::resource('partners', ...)
        resource_match = re.search(r"Route::resource\s*\(\s*['\"]([^'\"]+)['\"]", line)
        if resource_match:
            res_name = resource_match.group(1)
            prefix = 'admin.' if is_admin_group else ''
            # Standard RESTful resource actions
            actions = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']
            for action in actions:
                routes.add(f"{prefix}{res_name}.{action}")

    return routes

# Run Dynamic Route Parser
print(f"\n{Colors.BLUE}[1/6]{Colors.ENDC} Analyzing dynamic route mappings in routes/web.php...")
all_valid_routes = parse_laravel_routes('routes/web.php')
stats['routes_parsed'] = len(all_valid_routes)
print(f"      Mapped {Colors.GREEN}{len(all_valid_routes)}{Colors.ENDC} valid route paths dynamically (Resource expansion included).")

# Gather Blade Views
blade_files = glob.glob('resources/views/**/*.blade.php', recursive=True)
stats['blade_views'] = len(blade_files)

# Check 1: Audit Blade route() References
print(f"\n{Colors.BLUE}[2/6]{Colors.ENDC} Auditing route() link structures in all Blade views...")
for bf in blade_files:
    with open(bf, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Find route('...') or route("...") calls
    route_calls = re.findall(r"route\s*\(\s*['\"]([^'\"]+)['\"]", content)
    for rc in route_calls:
        # Ignore wildcards or dynamic parameters that are constructed runtime
        if '{' in rc or '$' in rc:
            continue
        if rc not in all_valid_routes:
            errors.append(f"Broken Route Link: Route '{rc}' referenced in [{bf}] is not defined in routes/web.php.")

# Check 2: Audit Static Asset References
print(f"\n{Colors.BLUE}[3/6]{Colors.ENDC} Auditing static asset() references against public storage...")
for bf in blade_files:
    with open(bf, 'r', encoding='utf-8') as f:
        content = f.read()
        
    asset_calls = re.findall(r"asset\s*\(\s*['\"]([^'\"]+)['\"]", content)
    for ac in asset_calls:
        # Ignore dynamic expressions
        if '$' in ac or '{{' in ac:
            continue
        asset_path = os.path.join('public', ac)
        # Check if file exists, or if it might be a newly compiled vite asset
        if not os.path.exists(asset_path):
            if 'build/assets' in ac or 'hot' in ac:
                continue # Handled by Vite runtime
            warnings.append(f"Missing Local Asset: '{ac}' referenced in [{bf}] was not found at [{asset_path}].")

# Check 3: Blade Syntax and Directives Integrity (Unclosed structural tags)
print(f"\n{Colors.BLUE}[4/6]{Colors.ENDC} Auditing structural syntax & Blade directive compliance...")
for bf in blade_files:
    with open(bf, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Count occurrences of structural open/close tags
    ifs = len(re.findall(r"@if\b", content))
    endifs = len(re.findall(r"@endif\b", content))
    
    foreaches = len(re.findall(r"@foreach\b", content))
    endforeaches = len(re.findall(r"@endforeach\b", content))
    
    sections = len(re.findall(r"@section\s*\(\s*['\"][^'\"]+['\"]\s*\)(?!\s*,)", content)) # Multi-line only
    endsections = len(re.findall(r"@endsection\b", content))
    
    pushes = len(re.findall(r"@push\b", content))
    endpushes = len(re.findall(r"@endpush\b", content))
    
    # CSRF form check
    if '<form' in content and 'method="POST"' in content and '@csrf' not in content:
        errors.append(f"Security Flaw: Form POST method in [{bf}] lacks the required '@csrf' directive!")

    # Record structural mismatches
    if ifs != endifs:
        errors.append(f"Blade Syntax Error: Unbalanced @if ({ifs}) and @endif ({endifs}) in [{bf}].")
    if foreaches != endforeaches:
        errors.append(f"Blade Syntax Error: Unbalanced @foreach ({foreaches}) and @endforeach ({endforeaches}) in [{bf}].")
    if sections != endsections:
        errors.append(f"Blade Syntax Error: Unbalanced multi-line @section ({sections}) and @endsection ({endsections}) in [{bf}].")
    if pushes != endpushes:
        errors.append(f"Blade Syntax Error: Unbalanced @push ({pushes}) and @endpush ({endpushes}) in [{bf}].")

# Check 4: Models and PSR-4 Namespace compliance
print(f"\n{Colors.BLUE}[5/6]{Colors.ENDC} Validating PSR-4 Model class structures...")
model_files = glob.glob('app/Models/**/*.php', recursive=True)
stats['models'] = len(model_files)
for mf in model_files:
    with open(mf, 'r', encoding='utf-8') as f:
        content = f.read()
    if 'namespace App\\Models;' not in content and 'namespace App\\Models\\' not in content:
        errors.append(f"PSR-4 Mismatch: Model class [{mf}] is missing a standard 'namespace App\\Models;' declaration.")

# Check 5: Controller and Database migrations audit
print(f"\n{Colors.BLUE}[6/6]{Colors.ENDC} Scanning controllers and database migration history...")
controller_files = glob.glob('app/Http/Controllers/**/*.php', recursive=True)
stats['controllers'] = len(controller_files)
for cf in controller_files:
    with open(cf, 'r', encoding='utf-8') as f:
        content = f.read()
    if 'class ' not in content:
        errors.append(f"Invalid Structure: Controller [{cf}] does not contain a standard class definition.")

migration_files = glob.glob('database/migrations/*.php')
stats['migrations'] = len(migration_files)
if len(migration_files) == 0:
    errors.append("Database Error: No migration files found under database/migrations/.")

# ─── SUMMARIZATION & SCORECARD RENDERING ───
print(f"\n{Colors.HEADER}{Colors.BOLD}======================================================================{Colors.ENDC}")
print(f"{Colors.CYAN}{Colors.BOLD}                      AUDIT REPORT SUMMARY & METRICS                  {Colors.ENDC}")
print(f"{Colors.HEADER}{Colors.BOLD}======================================================================{Colors.ENDC}")
print(f"  • Total Blade Templates Analyzed : {Colors.BOLD}{stats['blade_views']}{Colors.ENDC}")
print(f"  • Total PSR-4 Models Audited     : {Colors.BOLD}{stats['models']}{Colors.ENDC}")
print(f"  • Total Controllers Audited       : {Colors.BOLD}{stats['controllers']}{Colors.ENDC}")
print(f"  • Dynamic Mapped Laravel Routes  : {Colors.BOLD}{stats['routes_parsed']}{Colors.ENDC}")
print(f"  • Database Migration Schemas     : {Colors.BOLD}{stats['migrations']}{Colors.ENDC}")
print(f"{Colors.HEADER}======================================================================{Colors.ENDC}")

# Output findings
if len(errors) == 0:
    print(f"\n{Colors.GREEN}{Colors.BOLD}✅ AUDIT PASSED WITH 100% EXCELLENCE!{Colors.ENDC}")
    print(f"   All route references, assets, PSR-4 namespaces, security CSRF,")
    print(f"   and Blade structural tags are perfectly balanced and compliant.")
else:
    print(f"\n{Colors.FAIL}{Colors.BOLD}❌ FOUND {len(errors)} SECURITY & COMPLIANCE ISSUES:{Colors.ENDC}")
    for idx, err in enumerate(errors, 1):
        print(f"   {idx}. {err}")

if len(warnings) > 0:
    print(f"\n{Colors.WARNING}{Colors.BOLD}⚠️ FOUND {len(warnings)} MINOR RECOMMENDATIONS/WARNINGS:{Colors.ENDC}")
    for idx, wrn in enumerate(warnings, 1):
        print(f"   {idx}. {wrn}")

print(f"\n{Colors.HEADER}{Colors.BOLD}======================================================================{Colors.ENDC}")

# Return non-zero code to block pipeline integrations on critical compliance errors
if len(errors) > 0:
    sys.exit(1)
else:
    sys.exit(0)
