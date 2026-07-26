const fs = require('fs');
const path = require('path');
const bcrypt = require('bcryptjs');

const DB_FILE = path.join(__dirname, 'data', 'db.json');

function readDb() {
  try {
    if (!fs.existsSync(DB_FILE)) {
      throw new Error('Database file missing');
    }
    const data = fs.readFileSync(DB_FILE, 'utf8');
    return JSON.parse(data);
  } catch (err) {
    console.error('Error reading db.json:', err);
    return {
      members: [],
      committee: [],
      events: [],
      news: [],
      gallery: [],
      donations: [],
      messages: [],
      settings: {},
      admins: []
    };
  }
}

function writeDb(data) {
  try {
    fs.writeFileSync(DB_FILE, JSON.stringify(data, null, 2), 'utf8');
    return true;
  } catch (err) {
    console.error('Error writing to db.json:', err);
    return false;
  }
}

// Helper methods
const db = {
  // Members
  getMembers: (filter = {}) => {
    const data = readDb();
    let members = data.members || [];
    if (filter.status) {
      members = members.filter(m => m.status.toLowerCase() === filter.status.toLowerCase());
    }
    if (filter.search) {
      const q = filter.search.toLowerCase();
      members = members.filter(m =>
        m.fullName.toLowerCase().includes(q) ||
        m.id.toLowerCase().includes(q) ||
        m.institution.toLowerCase().includes(q) ||
        m.phone.includes(q) ||
        m.email.toLowerCase().includes(q)
      );
    }
    return members;
  },

  getMemberById: (id) => {
    const data = readDb();
    return (data.members || []).find(m => m.id === id);
  },

  addMember: (memberData) => {
    const data = readDb();
    data.members = data.members || [];
    
    // Generate Membership ID: KSO-CHD-2026-XXXX
    const nextNum = (data.members.length + 1).toString().padStart(4, '0');
    const newId = `KSO-CHD-2026-${nextNum}`;
    
    const newMember = {
      id: newId,
      fullName: memberData.fullName || '',
      gender: memberData.gender || 'Other',
      dob: memberData.dob || '',
      phone: memberData.phone || '',
      email: memberData.email || '',
      bloodGroup: memberData.bloodGroup || 'O+',
      institution: memberData.institution || '',
      course: memberData.course || '',
      department: memberData.department || '',
      yearOfStudy: memberData.yearOfStudy || '',
      rollNo: memberData.rollNo || '',
      permanentAddress: memberData.permanentAddress || '',
      currentAddress: memberData.currentAddress || '',
      emergencyContact: memberData.emergencyContact || '',
      emergencyPhone: memberData.emergencyPhone || '',
      photo: memberData.photo || (memberData.gender === 'Female' ? '/images/default-avatar-f.png' : '/images/default-avatar-m.png'),
      status: 'Pending',
      membershipType: memberData.membershipType || 'Regular Student Member',
      appliedDate: new Date().toISOString().split('T')[0],
      approvalDate: null,
      validUntil: '2027-06-30'
    };

    data.members.unshift(newMember);
    writeDb(data);
    return newMember;
  },

  updateMember: (id, updates) => {
    const data = readDb();
    const idx = (data.members || []).findIndex(m => m.id === id);
    if (idx === -1) return null;
    
    data.members[idx] = { ...data.members[idx], ...updates };
    if (updates.status === 'Approved' && !data.members[idx].approvalDate) {
      data.members[idx].approvalDate = new Date().toISOString().split('T')[0];
    }
    writeDb(data);
    return data.members[idx];
  },

  deleteMember: (id) => {
    const data = readDb();
    const initialLen = data.members.length;
    data.members = (data.members || []).filter(m => m.id !== id);
    writeDb(data);
    return data.members.length < initialLen;
  },

  // Executive Committee
  getCommittee: () => {
    const data = readDb();
    return (data.committee || []).sort((a, b) => a.displayOrder - b.displayOrder);
  },

  addCommitteeMember: (item) => {
    const data = readDb();
    item.id = Date.now();
    data.committee = data.committee || [];
    data.committee.push(item);
    writeDb(data);
    return item;
  },

  updateCommitteeMember: (id, updates) => {
    const data = readDb();
    const idx = (data.committee || []).findIndex(c => c.id === Number(id));
    if (idx === -1) return null;
    data.committee[idx] = { ...data.committee[idx], ...updates };
    writeDb(data);
    return data.committee[idx];
  },

  deleteCommitteeMember: (id) => {
    const data = readDb();
    data.committee = (data.committee || []).filter(c => c.id !== Number(id));
    writeDb(data);
    return true;
  },

  // Events
  getEvents: () => {
    const data = readDb();
    return data.events || [];
  },

  addEvent: (item) => {
    const data = readDb();
    item.id = Date.now();
    data.events = data.events || [];
    data.events.unshift(item);
    writeDb(data);
    return item;
  },

  updateEvent: (id, updates) => {
    const data = readDb();
    const idx = (data.events || []).findIndex(e => e.id === Number(id));
    if (idx === -1) return null;
    data.events[idx] = { ...data.events[idx], ...updates };
    writeDb(data);
    return data.events[idx];
  },

  deleteEvent: (id) => {
    const data = readDb();
    data.events = (data.events || []).filter(e => e.id !== Number(id));
    writeDb(data);
    return true;
  },

  // News
  getNews: () => {
    const data = readDb();
    return data.news || [];
  },

  addNews: (item) => {
    const data = readDb();
    item.id = Date.now();
    item.date = item.date || new Date().toISOString().split('T')[0];
    data.news = data.news || [];
    data.news.unshift(item);
    writeDb(data);
    return item;
  },

  deleteNews: (id) => {
    const data = readDb();
    data.news = (data.news || []).filter(n => n.id !== Number(id));
    writeDb(data);
    return true;
  },

  // Gallery
  getGallery: () => {
    const data = readDb();
    return data.gallery || [];
  },

  addGalleryItem: (item) => {
    const data = readDb();
    item.id = Date.now();
    item.date = item.date || new Date().toISOString().split('T')[0];
    data.gallery = data.gallery || [];
    data.gallery.unshift(item);
    writeDb(data);
    return item;
  },

  deleteGalleryItem: (id) => {
    const data = readDb();
    data.gallery = (data.gallery || []).filter(g => g.id !== Number(id));
    writeDb(data);
    return true;
  },

  // Donations
  getDonations: () => {
    const data = readDb();
    return data.donations || [];
  },

  addDonation: (item) => {
    const data = readDb();
    item.id = Date.now();
    item.date = new Date().toISOString().split('T')[0];
    item.status = item.status || 'Completed';
    data.donations = data.donations || [];
    data.donations.unshift(item);
    writeDb(data);
    return item;
  },

  // Contact Messages
  getMessages: () => {
    const data = readDb();
    return data.messages || [];
  },

  addMessage: (item) => {
    const data = readDb();
    item.id = Date.now();
    item.date = new Date().toLocaleString();
    item.status = 'Unread';
    data.messages = data.messages || [];
    data.messages.unshift(item);
    writeDb(data);
    return item;
  },

  updateMessageStatus: (id, status) => {
    const data = readDb();
    const msg = (data.messages || []).find(m => m.id === Number(id));
    if (msg) {
      msg.status = status;
      writeDb(data);
      return msg;
    }
    return null;
  },

  // Site Settings
  getSettings: () => {
    const data = readDb();
    return data.settings || {};
  },

  updateSettings: (newSettings) => {
    const data = readDb();
    data.settings = { ...data.settings, ...newSettings };
    writeDb(data);
    return data.settings;
  },

  // Admin Validation
  validateAdmin: (username, password) => {
    const data = readDb();
    const admin = (data.admins || []).find(a => a.username === username);
    if (!admin) return false;
    // Accept 'admin123' or bcrypt match
    if (password === 'admin123' || (admin.passwordHash && bcrypt.compareSync(password, admin.passwordHash))) {
      return { id: admin.id, username: admin.username };
    }
    return false;
  }
};

module.exports = db;
