const express = require('express');
const cors = require('cors');
const path = require('path');
const fs = require('fs');
const multer = require('multer');
const session = require('express-session');
const QRCode = require('qrcode');
const db = require('./db');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

app.use(session({
  secret: 'kso-chandigarh-secret-key-2026',
  resave: false,
  saveUninitialized: false,
  cookie: { maxAge: 24 * 60 * 60 * 1000 } // 24 hours
}));

// Serve static files
app.use(express.static(path.join(__dirname, 'public')));

// Configure Multer for file uploads
const uploadDir = path.join(__dirname, 'public', 'uploads');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, uploadDir);
  },
  filename: (req, file, cb) => {
    const ext = path.extname(file.originalname);
    const uniqueName = `${Date.now()}-${Math.round(Math.random() * 1E9)}${ext}`;
    cb(null, uniqueName);
  }
});

const upload = multer({
  storage: storage,
  limits: { fileSize: 5 * 1024 * 1024 }, // 5MB
  fileFilter: (req, file, cb) => {
    if (file.mimetype.startsWith('image/')) {
      cb(null, true);
    } else {
      cb(new Error('Only image files are allowed!'), false);
    }
  }
});

// Admin Auth Helper Middleware
function requireAdmin(req, res, next) {
  if (req.session && req.session.isAdmin) {
    return next();
  }
  return res.status(401).json({ success: false, message: 'Unauthorized. Admin access required.' });
}

// ─── ROUTES ───

// QR Code Generator API
app.get('/api/qr/:id', async (req, res) => {
  try {
    const text = req.params.id;
    const qrDataUrl = await QRCode.toDataURL(text, {
      margin: 1,
      color: { dark: '#003566', light: '#ffffff' }
    });
    const base64Data = qrDataUrl.replace(/^data:image\/png;base64,/, "");
    const img = Buffer.from(base64Data, 'base64');
    res.writeHead(200, {
      'Content-Type': 'image/png',
      'Content-Length': img.length
    });
    res.end(img);
  } catch (err) {
    res.status(500).json({ success: false, message: 'QR Code generation failed' });
  }
});

// Admin Authentication
app.post('/api/admin/login', (req, res) => {
  const { username, password } = req.body;
  const admin = db.validateAdmin(username, password);
  if (admin) {
    req.session.isAdmin = true;
    req.session.adminUser = admin.username;
    return res.json({ success: true, message: 'Login successful', username: admin.username });
  } else {
    return res.status(400).json({ success: false, message: 'Invalid username or password' });
  }
});

app.post('/api/admin/logout', (req, res) => {
  req.session.destroy();
  res.json({ success: true, message: 'Logged out successfully' });
});

app.get('/api/admin/check', (req, res) => {
  res.json({ isAdmin: !!(req.session && req.session.isAdmin), username: req.session ? req.session.adminUser : null });
});

// ── MEMBERS API ──

// Public Registration
app.post('/api/members/register', upload.single('photoFile'), (req, res) => {
  try {
    const memberData = req.body;
    if (req.file) {
      memberData.photo = `/uploads/${req.file.filename}`;
    }
    const newMember = db.addMember(memberData);
    res.json({ success: true, message: 'Registration submitted successfully!', member: newMember });
  } catch (err) {
    console.error('Registration Error:', err);
    res.status(500).json({ success: false, message: 'Failed to process registration' });
  }
});

// Member Verification Endpoint
app.get('/api/members/verify/:id', (req, res) => {
  const member = db.getMemberById(req.params.id);
  if (!member) {
    return res.status(404).json({ success: false, message: 'Member ID not found in KSO database.' });
  }
  res.json({
    success: true,
    member: {
      id: member.id,
      fullName: member.fullName,
      gender: member.gender,
      institution: member.institution,
      course: member.course,
      yearOfStudy: member.yearOfStudy,
      status: member.status,
      photo: member.photo,
      membershipType: member.membershipType,
      validUntil: member.validUntil
    }
  });
});

// Member Portal Login (by ID or Email + Phone)
app.post('/api/members/portal-login', (req, res) => {
  const { identifier } = req.body;
  if (!identifier) return res.status(400).json({ success: false, message: 'Please enter Membership ID or Email' });
  
  const allMembers = db.getMembers();
  const found = allMembers.find(m => 
    m.id.toLowerCase() === identifier.trim().toLowerCase() || 
    m.email.toLowerCase() === identifier.trim().toLowerCase()
  );

  if (found) {
    res.json({ success: true, member: found });
  } else {
    res.status(404).json({ success: false, message: 'No registered member found with those details.' });
  }
});

// Get Members (Admin or search)
app.get('/api/members', (req, res) => {
  const { status, search } = req.query;
  const members = db.getMembers({ status, search });
  res.json({ success: true, members });
});

// Get Single Member
app.get('/api/members/:id', (req, res) => {
  const member = db.getMemberById(req.params.id);
  if (member) {
    res.json({ success: true, member });
  } else {
    res.status(404).json({ success: false, message: 'Member not found' });
  }
});

// Admin Update Member Status
app.put('/api/members/:id/status', requireAdmin, (req, res) => {
  const { status } = req.body;
  const updated = db.updateMember(req.params.id, { status });
  if (updated) {
    res.json({ success: true, member: updated });
  } else {
    res.status(404).json({ success: false, message: 'Member not found' });
  }
});

// Admin Update Member Info
app.put('/api/members/:id', requireAdmin, upload.single('photoFile'), (req, res) => {
  const updates = { ...req.body };
  if (req.file) {
    updates.photo = `/uploads/${req.file.filename}`;
  }
  const updated = db.updateMember(req.params.id, updates);
  if (updated) {
    res.json({ success: true, member: updated });
  } else {
    res.status(404).json({ success: false, message: 'Member not found' });
  }
});

// Admin Delete Member
app.delete('/api/members/:id', requireAdmin, (req, res) => {
  const ok = db.deleteMember(req.params.id);
  if (ok) {
    res.json({ success: true, message: 'Member deleted' });
  } else {
    res.status(404).json({ success: false, message: 'Member not found' });
  }
});

// Export Members CSV
app.get('/api/members/export/csv', requireAdmin, (req, res) => {
  const members = db.getMembers();
  if (!members || members.length === 0) {
    return res.status(400).send('No members to export');
  }

  const headers = ['ID', 'Full Name', 'Gender', 'Phone', 'Email', 'Blood Group', 'Institution', 'Course', 'Department', 'Year', 'Permanent Address', 'Current Address', 'Emergency Contact', 'Emergency Phone', 'Status', 'Applied Date'];
  
  let csv = headers.join(',') + '\n';
  members.forEach(m => {
    const row = [
      `"${m.id}"`,
      `"${m.fullName}"`,
      `"${m.gender}"`,
      `"${m.phone}"`,
      `"${m.email}"`,
      `"${m.bloodGroup}"`,
      `"${m.institution}"`,
      `"${m.course}"`,
      `"${m.department}"`,
      `"${m.yearOfStudy}"`,
      `"${(m.permanentAddress || '').replace(/"/g, '""')}"`,
      `"${(m.currentAddress || '').replace(/"/g, '""')}"`,
      `"${(m.emergencyContact || '').replace(/"/g, '""')}"`,
      `"${m.emergencyPhone}"`,
      `"${m.status}"`,
      `"${m.appliedDate}"`
    ];
    csv += row.join(',') + '\n';
  });

  res.setHeader('Content-Type', 'text/csv');
  res.setHeader('Content-Disposition', 'attachment; filename="kso_members_export.csv"');
  res.status(200).send(csv);
});

// ── EXECUTIVE COMMITTEE API ──
app.get('/api/committee', (req, res) => {
  res.json({ success: true, committee: db.getCommittee() });
});

app.post('/api/committee', requireAdmin, upload.single('photoFile'), (req, res) => {
  const data = req.body;
  if (req.file) data.photo = `/uploads/${req.file.filename}`;
  const added = db.addCommitteeMember(data);
  res.json({ success: true, item: added });
});

app.put('/api/committee/:id', requireAdmin, upload.single('photoFile'), (req, res) => {
  const data = req.body;
  if (req.file) data.photo = `/uploads/${req.file.filename}`;
  const updated = db.updateCommitteeMember(req.params.id, data);
  res.json({ success: true, item: updated });
});

app.delete('/api/committee/:id', requireAdmin, (req, res) => {
  db.deleteCommitteeMember(req.params.id);
  res.json({ success: true, message: 'Committee member deleted' });
});

// ── EVENTS API ──
app.get('/api/events', (req, res) => {
  res.json({ success: true, events: db.getEvents() });
});

app.post('/api/events', requireAdmin, upload.single('imageFile'), (req, res) => {
  const data = req.body;
  if (req.file) data.image = `/uploads/${req.file.filename}`;
  const added = db.addEvent(data);
  res.json({ success: true, item: added });
});

app.put('/api/events/:id', requireAdmin, upload.single('imageFile'), (req, res) => {
  const data = req.body;
  if (req.file) data.image = `/uploads/${req.file.filename}`;
  const updated = db.updateEvent(req.params.id, data);
  res.json({ success: true, item: updated });
});

app.delete('/api/events/:id', requireAdmin, (req, res) => {
  db.deleteEvent(req.params.id);
  res.json({ success: true, message: 'Event deleted' });
});

// ── NEWS & ANNOUNCEMENTS API ──
app.get('/api/news', (req, res) => {
  res.json({ success: true, news: db.getNews() });
});

app.post('/api/news', requireAdmin, (req, res) => {
  const added = db.addNews(req.body);
  res.json({ success: true, item: added });
});

app.delete('/api/news/:id', requireAdmin, (req, res) => {
  db.deleteNews(req.params.id);
  res.json({ success: true, message: 'News item deleted' });
});

// ── GALLERY API ──
app.get('/api/gallery', (req, res) => {
  res.json({ success: true, gallery: db.getGallery() });
});

app.post('/api/gallery', requireAdmin, upload.single('imageFile'), (req, res) => {
  const data = req.body;
  if (req.file) data.imageUrl = `/uploads/${req.file.filename}`;
  const added = db.addGalleryItem(data);
  res.json({ success: true, item: added });
});

app.delete('/api/gallery/:id', requireAdmin, (req, res) => {
  db.deleteGalleryItem(req.params.id);
  res.json({ success: true, message: 'Gallery item deleted' });
});

// ── DONATIONS API ──
app.get('/api/donations', (req, res) => {
  res.json({ success: true, donations: db.getDonations() });
});

app.post('/api/donations', (req, res) => {
  const added = db.addDonation(req.body);
  res.json({ success: true, donation: added });
});

// ── CONTACT MESSAGES API ──
app.get('/api/messages', requireAdmin, (req, res) => {
  res.json({ success: true, messages: db.getMessages() });
});

app.post('/api/messages', (req, res) => {
  const added = db.addMessage(req.body);
  res.json({ success: true, message: 'Inquiry submitted successfully! We will get back to you soon.' });
});

app.put('/api/messages/:id/status', requireAdmin, (req, res) => {
  const updated = db.updateMessageStatus(req.params.id, req.body.status);
  res.json({ success: true, message: updated });
});

// ── SITE SETTINGS API ──
app.get('/api/settings', (req, res) => {
  res.json({ success: true, settings: db.getSettings() });
});

app.put('/api/settings', requireAdmin, (req, res) => {
  const updated = db.updateSettings(req.body);
  res.json({ success: true, settings: updated });
});

// Fallback to SPA index.html
app.use((req, res, next) => {
  if (req.method === 'GET' && !req.path.startsWith('/api')) {
    return res.sendFile(path.join(__dirname, 'public', 'index.html'));
  }
  next();
});

// Start Server if called directly
if (require.main === module) {
  app.listen(PORT, () => {
    console.log(`===================================================`);
    console.log(`  KSO CHANDIGARH WEBSITE & CMS RUNNING ON PORT ${PORT}`);
    console.log(`  Access site at http://localhost:${PORT}`);
    console.log(`===================================================`);
  });
}

module.exports = app;
