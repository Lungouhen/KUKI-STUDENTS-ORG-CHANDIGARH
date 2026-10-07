@extends('layouts.app')

@section('title', 'Student Member Portal Dashboard | KSO Chandigarh')

@section('content')

@php
    $sidebarAds = \App\Models\Advertisement::getActiveByPlacement('Portal_Sidebar');
    $feedAds = \App\Models\Advertisement::getActiveByPlacement('Feed_Banner');
@endphp

<div class="bg-primary text-white py-4 mb-4" style="background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%) !important;">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Welcome, {{ $member->full_name }}!</h2>
        <p class="small text-light opacity-90 mb-0">Member ID: <strong>{{ $member->id }}</strong> • Status: <span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $member->status }}</span></p>
    </div>
</div>

<div class="container my-5" x-data="{ activeTab: 'overview' }">
    
    <!-- ─── RE-ENHANCED STUDENT PORTAL NAVIGATION TABS ─── -->
    <div class="portal-tab-nav mb-4">
        <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'active' : ''" class="portal-tab-btn">
            <i class="fa-solid fa-gauge"></i> Overview & Digital ID
        </button>
        <button @click="activeTab = 'election'" :class="activeTab === 'election' ? 'active' : ''" class="portal-tab-btn">
            <i class="fa-solid fa-check-to-slot"></i> Election Booth 🗳️
        </button>
        <button @click="activeTab = 'accommodation'" :class="activeTab === 'accommodation' ? 'active' : ''" class="portal-tab-btn">
            <i class="fa-solid fa-hotel"></i> Hostel & PG Finder 🏡
        </button>
        <button @click="activeTab = 'academic'" :class="activeTab === 'academic' ? 'active' : ''" class="portal-tab-btn">
            <i class="fa-solid fa-book-open-reader"></i> Academic Desk 📚
        </button>
    </div>

    <!-- ─── TAB 1: OVERVIEW & ID CARD PANORAMA ─── -->
    <div x-show="activeTab === 'overview'" x-transition x-cloak>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="text-center">
                    <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-id-card me-2"></i> Your Official Digital ID Card</h5>
                    
                    <div class="id-card-wrapper shadow-lg text-start my-3 mx-auto" id="idCardPrintArea" style="max-width: 320px;">
                        <div class="id-card-header">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <img src="{{ asset('images/kso-logo.jpg') }}" onerror="this.src='/images/default-avatar-m.png'">
                                <div>
                                    <h5 class="mb-0 text-white">KSO CHANDIGARH</h5>
                                    <p class="text-warning fw-bold small">Kuki Students' Organisation</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="id-card-body">
                            <div class="id-card-photo-container">
                                <img src="{{ asset($member->photo) }}" onerror="this.src='/images/default-avatar-m.png'">
                            </div>

                            <div class="text-center">
                                <div class="id-card-name">{{ $member->full_name }}</div>
                                <div class="id-card-num">{{ $member->id }}</div>
                                <div class="badge bg-primary rounded-pill px-3 py-1 extra-small">{{ $member->designation ?? 'Student Member' }}</div>
                            </div>

                            <table class="id-card-details w-100 mt-2">
                                <tr><td class="label">College:</td><td class="fw-bold">{{ $member->institution }}</td></tr>
                                <tr><td class="label">Course:</td><td>{{ $member->course }}</td></tr>
                                <tr><td class="label">Category:</td><td class="fw-bold">{{ $member->membership_category }}</td></tr>
                                <tr><td class="label">Status:</td><td><span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-0 extra-small">{{ strtoupper($member->status) }}</span></td></tr>
                            </table>
                        </div>

                        <div class="id-card-footer">
                            <div>
                                <div class="fw-bold text-warning extra-small">VALID UNTIL: {{ $member->valid_until ? $member->valid_until->format('Y-m-d') : '2027-06-30' }}</div>
                                <div class="extra-small opacity-75">Recognized by KSO General HQ</div>
                            </div>
                            @php
                                $verifyUrl = route('membership.verifyDirect', $member->id);
                                $qrUrl = "https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=" . urlencode($verifyUrl) . "&choe=UTF-8";
                            @endphp
                            <img src="{{ $qrUrl }}" width="45" height="45" class="rounded bg-white p-1" alt="Verification QR">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button onclick="window.print()" class="btn btn-accent btn-sm rounded-pill px-4 fw-bold mb-2">
                            <i class="fa-solid fa-print me-1"></i> Print / Download
                        </button>
                        <a href="{{ route('membership.portalLogout') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 mb-2">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                        </a>
                    </div>
                </div>
                
                <!-- Quick Navigation to Election booth -->
                <div class="card shadow-sm border-0 rounded-4 p-4 mt-4 bg-white text-center">
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-check-to-slot me-1"></i> Student Elections</h6>
                    <p class="extra-small text-muted">KSO Executive Council student body election and active voting booth is open.</p>
                    <button @click.prevent="activeTab = 'election'" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold">
                        Go to Election Booth <i class="fa-solid fa-vote-yea ms-1"></i>
                    </button>
                </div>

                <!-- ─── STUDENT SPONSORS (AD SYSTEM SIDEBAR) ─── -->
                @if($sidebarAds->count() > 0)
                    @foreach($sidebarAds as $ad)
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mt-4 bg-white p-0 text-start hover-lift">
                            <div class="position-relative">
                                <img src="{{ $ad->image_url }}" class="img-fluid w-100" style="height: 150px; object-fit: cover;">
                                <span class="position-absolute top-0 end-0 m-2 badge bg-success text-white fw-bold extra-small" style="font-size: 0.65rem;"><i class="fa-solid fa-bullhorn me-1"></i> Sponsored</span>
                            </div>
                            <div class="p-3">
                                <small class="text-muted extra-small fw-bold text-uppercase">{{ $ad->company_name }}</small>
                                <h6 class="fw-bold text-dark mt-1 mb-2" style="font-size: 0.85rem;">{{ $ad->title }}</h6>
                                <a href="{{ route('ads.click', $ad->id) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill w-100 extra-small fw-bold">Learn More <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.7rem;"></i></a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="col-lg-8">
                <!-- Dashboard Statistics Overview -->
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-gauge text-primary me-2"></i> Dashboard Overview</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Welcome Message</div>
                                <div class="fw-bold text-dark text-truncate">Welcome, {{ $member->full_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3">
                                <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Member ID</div>
                                <div class="fw-bold text-primary">{{ $member->id }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3">
                                <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Status</div>
                                <span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning' }}">{{ $member->status }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Total Fees Paid</div>
                                <div class="fw-bold text-success">₹{{ number_format($totalFeesPaid) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Payments Made</div>
                                <div class="fw-bold text-dark">{{ $paymentsCount }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Type</div>
                                <div class="fw-bold text-dark">{{ $member->membership_category }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Fee {{ $currentPeriod }}</div>
                                <span class="badge {{ $currentFeePaid ? 'bg-success' : 'bg-warning text-dark' }}">{{ $currentFeePaid ? 'Paid' : 'Due' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Membership Fee History -->
                <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-black text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-receipt text-primary me-2"></i> Membership Fee History</h5>
                    @if($feePayments->isEmpty())
                        <p class="text-muted small mb-0">No fee payments recorded yet. Pay your membership fee at the KSO office to activate full benefits for {{ $currentPeriod }}.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr class="extra-small text-muted text-uppercase">
                                        <th>Period</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Receipt No.</th>
                                        <th>Paid On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feePayments as $payment)
                                        <tr class="small">
                                            <td class="fw-bold">{{ $payment->period }}</td>
                                            <td class="text-success fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td><code class="extra-small">{{ $payment->voucher_no ?? $payment->reference_no ?? '—' }}</code></td>
                                            <td>{{ $payment->paid_on?->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Campus Social Feed -->
                <div class="social-feed-container mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-black text-dark mb-0"><i class="fa-solid fa-square-rss text-primary me-2"></i> KSO Campus Social Feed</h5>
                        <span class="badge bg-success-lt text-success rounded-pill px-3 py-1 extra-small"><i class="fa-solid fa-circle me-1 animate-pulse"></i> Feed Live</span>
                    </div>

                    <!-- Create Post Section -->
                    <div class="share-update-card shadow-sm rounded-4" x-data="{ selectedCategory: 'Discussion' }">
                        <form action="{{ route('membership.storeStudentPost') }}" method="POST">
                            @csrf
                            <input type="hidden" name="category" :value="selectedCategory">
                            
                            <div class="share-input-row">
                                <div class="social-avatar-container shadow-sm">
                                    <img src="{{ asset($member->photo) }}" onerror="this.src='/images/default-avatar-m.png'">
                                </div>
                                <div class="flex-grow-1">
                                    <textarea name="content" class="share-textarea" placeholder="What's on your mind, {{ explode(' ', $member->full_name)[0] }}? Share admission info, hosteling tips, or discussion posts..." rows="3" required></textarea>
                                </div>
                            </div>

                            <div class="share-actions-row">
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="extra-small text-muted fw-bold me-1">Tag with:</span>
                                    <button type="button" @click="selectedCategory = 'Discussion'" :class="selectedCategory === 'Discussion' ? 'active' : ''" class="share-tag-pill">#Discussion</button>
                                    <button type="button" @click="selectedCategory = 'Housing'" :class="selectedCategory === 'Housing' ? 'active' : ''" class="share-tag-pill">#Housing</button>
                                    <button type="button" @click="selectedCategory = 'Admission'" :class="selectedCategory === 'Admission' ? 'active' : ''" class="share-tag-pill">#Admission</button>
                                    <button type="button" @click="selectedCategory = 'CampusLife'" :class="selectedCategory === 'CampusLife' ? 'active' : ''" class="share-tag-pill">#CampusLife</button>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold">
                                        Share <i class="fa-solid fa-paper-plane ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Posts List -->
                    @if($posts->count() == 0)
                        <div class="empty-state-card">
                            <div class="empty-icon"><i class="fa-solid fa-feed"></i></div>
                            <h6 class="fw-bold text-dark">No updates shared yet</h6>
                            <p class="text-muted extra-small">Be the first to share an update or start a discussion with fellow students!</p>
                        </div>
                    @else
                        @foreach($posts as $post)
                            @php
                                $isOfficial = ($post->category === 'Notice' || $post->category === 'Announcement' || $post->author === 'Executive Desk');
                                $baseLikes = ($post->id * 7 + 13) % 43;
                            @endphp
                            
                            <div class="social-post-card shadow-sm rounded-4" x-data="{ 
                                liked: localStorage.getItem('post_liked_' + {{ $post->id }}) === 'true', 
                                likesCount: {{ $baseLikes }},
                                commentsOpen: false,
                                comments: [
                                    { author: 'Welfare Cell', text: 'Please reach out to the KSO helpline if anyone needs direct assistance! 📞' },
                                    { author: 'MCM Student', text: 'This is extremely helpful for us newcomers, thank you KSO!' }
                                ],
                                newComment: '',
                                toggleLike() {
                                    this.liked = !this.liked;
                                    localStorage.setItem('post_liked_' + {{ $post->id }}, this.liked);
                                    if (this.liked) {
                                        this.likesCount++;
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'You liked this post!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    } else {
                                        this.likesCount--;
                                    }
                                },
                                addComment() {
                                    if (this.newComment.trim() === '') return;
                                    this.comments.push({
                                        author: '{{ explode(' ', $member->full_name)[0] }} (You)',
                                        text: this.newComment
                                    });
                                    this.newComment = '';
                                },
                                sharePost() {
                                    navigator.clipboard.writeText(window.location.origin + '/members/portal?post=' + {{ $post->id }});
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Post link copied to clipboard!',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            }">
                                
                                <div class="social-header">
                                    <div class="social-author-info">
                                        <div class="social-avatar-container shadow-sm border-{{ $isOfficial ? 'warning' : 'primary' }}">
                                            @if($isOfficial)
                                                <img src="{{ asset('images/kso-logo.jpg') }}" onerror="this.src='/images/default-avatar-m.png'">
                                            @else
                                                <img src="/images/default-avatar-m.png" onerror="this.src='/images/default-avatar-m.png'">
                                            @endif
                                        </div>
                                        <div>
                                            <div class="social-author-name">
                                                {{ $post->author }}
                                                @if($isOfficial)
                                                    <span class="social-author-badge official"><i class="fa-solid fa-crown me-1 text-warning"></i> KSO Official</span>
                                                @else
                                                    <span class="social-author-badge"><i class="fa-solid fa-user-graduate me-1 text-teal"></i> Member</span>
                                                @endif
                                            </div>
                                            <span class="social-post-time">
                                                <i class="fa-regular fa-clock me-1"></i>
                                                @if($post->created_at)
                                                    {{ $post->created_at->diffForHumans() }}
                                                @else
                                                    {{ $post->date ? $post->date->format('Y-m-d') : 'Recently' }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary-lt text-primary px-3 py-1 rounded-pill extra-small">
                                        #{{ $post->category }}
                                    </span>
                                </div>

                                <div class="social-body">
                                    @php
                                        $rawContent = $post->content;
                                        $ytId = null;
                                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/i', $rawContent, $ytMatch)) {
                                            $ytId = $ytMatch[1];
                                        }
                                        $imgUrl = null;
                                        if (preg_match('/(https?:\/\/[^\s]+\.(?:jpg|jpeg|png|gif|webp))/i', $rawContent, $imgMatch)) {
                                            $imgUrl = $imgMatch[1];
                                        }
                                        $formattedContent = e($rawContent);
                                        $formattedContent = preg_replace('/(#\w+)/', '<span class="text-teal fw-bold">$1</span>', $formattedContent);
                                        $formattedContent = preg_replace_callback('/(https?:\/\/[^\s]+)/i', function($matches) {
                                            $url = $matches[1];
                                            return '<a href="' . $url . '" target="_blank" class="text-primary fw-semibold text-decoration-underline">' . (strlen($url) > 45 ? substr($url, 0, 42) . '...' : $url) . '</a>';
                                        }, $formattedContent);
                                    @endphp
                                    
                                    <p class="mb-0">{!! $formattedContent !!}</p>
                                    
                                    @if($imgUrl)
                                        <div class="mt-3 rounded-4 overflow-hidden border shadow-sm" style="max-height: 350px;">
                                            <img src="{{ $imgUrl }}" class="w-100 h-100" style="object-fit: cover; max-height: 350px;" alt="Attached Image" onerror="this.style.display='none'">
                                        </div>
                                    @endif
                                    
                                    @if($ytId)
                                        <div class="mt-3 rounded-4 overflow-hidden border shadow-sm ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/{{ $ytId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                </div>

                                <div class="social-actions">
                                    <button class="social-action-btn" :class="liked ? 'liked' : ''" @click="toggleLike()">
                                        <i class="fa-solid fa-heart"></i> <span x-text="likesCount"></span> Likes
                                    </button>
                                    <button class="social-action-btn" @click="commentsOpen = !commentsOpen">
                                        <i class="fa-solid fa-comment-dots"></i> <span x-text="comments.length"></span> Comments
                                    </button>
                                    <button class="social-action-btn" @click="sharePost()">
                                        <i class="fa-solid fa-share-nodes"></i> Share
                                    </button>
                                </div>

                                <div class="social-comments-drawer" x-show="commentsOpen" x-transition x-cloak>
                                    <div class="social-comment-thread">
                                        <template x-for="comment in comments">
                                            <div class="social-comment-item">
                                                <div class="comment-avatar bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="font-size: 0.7rem;">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                                <div class="comment-bubble shadow-sm">
                                                    <div class="comment-author-name" x-text="comment.author"></div>
                                                    <div class="comment-text" x-text="comment.text"></div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="comment-form-container">
                                        <input type="text" class="comment-input-field form-control" placeholder="Write a comment..." x-model="newComment" @keyup.enter="addComment()">
                                        <button class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" @click="addComment()">
                                            <i class="fa-solid fa-paper-plane" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @if($loop->first && $feedAds->count() > 0)
                                @foreach($feedAds as $ad)
                                    <div class="social-post-card overflow-hidden hover-lift border-warning-lt" style="border-left: 4px solid var(--amber-gold);">
                                        <div class="social-header py-2 bg-light bg-opacity-50">
                                            <div class="social-author-info">
                                                <div class="social-avatar-container shadow-sm border-warning d-flex align-items-center justify-content-center bg-warning-lt" style="width: 32px; height: 32px;">
                                                    <i class="fa-solid fa-rectangle-ad fs-6 text-warning"></i>
                                                </div>
                                                <div>
                                                    <div class="social-author-name text-dark" style="font-size: 0.82rem;">
                                                        {{ $ad->company_name }} <span class="badge bg-success text-white fw-bold ms-1" style="font-size: 0.6rem;"><i class="fa-solid fa-circle-nodes"></i> Sponsored Partner</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="social-body pb-2">
                                            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">{{ $ad->title }}</h6>
                                            <div class="rounded-3 overflow-hidden border">
                                                <img src="{{ $ad->image_url }}" class="img-fluid w-100" style="max-height: 240px; object-fit: cover;">
                                            </div>
                                        </div>
                                        <div class="p-3 bg-light bg-opacity-25 d-flex align-items-center justify-content-between border-top">
                                            <span class="extra-small text-muted fw-bold">KSO Chandigarh Trusted Student Sponsor Partner</span>
                                            <a href="{{ route('ads.click', $ad->id) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm">Visit Sponsor <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.75rem;"></i></a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </div>

                <!-- Student Account Details Table -->
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-user-gear text-primary me-2"></i> Student Account Details</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm extra-small">
                            <tr><th class="text-primary w-25">Membership ID:</th><td class="fw-bold">{{ $member->id }}</td></tr>
                            <tr><th class="text-primary">Designation:</th><td class="fw-bold text-warning">{{ $member->designation ?? 'Regular Student Member' }}</td></tr>
                            <tr><th class="text-primary">Join Date:</th><td>{{ $member->applied_date ? $member->applied_date->format('Y-m-d') : '2026-07-26' }}</td></tr>
                            <tr><th class="text-primary">Full Name:</th><td>{{ $member->full_name }}</td></tr>
                            <tr><th class="text-primary">Gender / DOB:</th><td>{{ $member->gender }} • {{ $member->dob ? $member->dob->format('Y-m-d') : '' }}</td></tr>
                            <tr><th class="text-primary">Phone / Email:</th><td>{{ $member->phone }} / {{ $member->email }}</td></tr>
                            <tr><th class="text-primary">Institution:</th><td>{{ $member->institution }}</td></tr>
                            <tr><th class="text-primary">Course & Dept:</th><td>{{ $member->course }} ({{ $member->department ?? 'N/A' }}) - {{ $member->year_of_study }}</td></tr>
                            <tr><th class="text-primary">Address:</th><td>{{ $member->current_address }}</td></tr>
                            <tr><th class="text-primary">Emergency:</th><td>{{ $member->emergency_contact }} ({{ $member->emergency_phone }})</td></tr>
                        </table>
                    </div>
                </div>

                <!-- Fees & Payment History -->
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-credit-card text-success me-2"></i> Fees & Payment History</h5>
                    <div class="table-responsive">
                        <table class="table table-sm extra-small align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Term</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-2026</td>
                                    <td>Registration</td>
                                    <td>₹250</td>
                                    <td><span class="badge bg-success">Success</span></td>
                                    <td>{{ $member->applied_date ? $member->applied_date->format('Y-m-d') : '2026-07-26' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Medical Relief Claim Desk -->
                <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-notes-medical text-danger me-2"></i> Medical Relief Claim Desk</h5>
                    
                    @if($medicalClaims->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold extra-small text-muted text-uppercase mb-2">My Recent Claims</h6>
                            @foreach($medicalClaims as $claim)
                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom extra-small">
                                    <div>
                                        <div class="fw-bold">{{ $claim->hospital_name }}</div>
                                        <div class="text-muted">Requested: ₹{{ number_format($claim->amount_requested) }}</div>
                                    </div>
                                    <span class="badge {{ $claim->status === 'Approved' ? 'bg-success' : ($claim->status === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ strtoupper($claim->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <p class="extra-small text-muted mb-3">If you are facing a medical emergency at PGIMER, GMCH-32, or any other hospital, you can submit a relief request here. KSO Chandigarh may provide partial financial assistance based on fund availability.</p>
                    
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#medicalClaimModal">
                        <i class="fa-solid fa-plus-circle me-1"></i> New Relief Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── TAB 2: INTERACTIVE ELECTRONIC VOTING BOOTH ─── -->
    <div x-show="activeTab === 'election'" x-transition x-cloak>
        @forelse($openElections as $election)
            @php
                $alreadyVoted = in_array($election->id, $votedElectionIds);
                $totalVotes = $election->candidates->sum('votes_received');
            @endphp
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h5 class="fw-black text-dark mb-0"><i class="fa-solid fa-vote-yea text-primary me-2"></i> KSO Executive Council Election — {{ $election->position }}{{ $election->term ? ' (' . $election->term->name . ')' : '' }}</h5>
                    <span class="badge bg-success text-white px-3 py-1 rounded-pill extra-small"><i class="fa-solid fa-circle me-1 animate-pulse"></i> Booth Open</span>
                </div>

                @if($alreadyVoted)
                    <!-- Locked Result Screen after voting -->
                    <div class="text-center py-5">
                        <div class="display-3 text-success mb-3"><i class="fa-solid fa-circle-check"></i></div>
                        <h4 class="fw-bold text-dark">Thank you! Your vote has been securely recorded</h4>
                        <p class="text-muted small mx-auto" style="max-width: 500px;">Participation is the key to student democracy. Your single secret ballot has been cast and double-voting prevention is locked for your student account ID.</p>

                        <div class="mt-4 p-4 bg-light rounded-4 text-start mx-auto" style="max-width: 450px;">
                            <h6 class="fw-bold text-primary mb-3 text-uppercase extra-small tracking-wider text-center"><i class="fa-solid fa-square-poll-vertical me-1"></i> Live Voting Poll Statistics</h6>

                            @foreach($election->candidates as $candidate)
                                @php $share = $totalVotes > 0 ? round($candidate->votes_received / $totalVotes * 100) : 0; @endphp
                                <div class="{{ $loop->last ? 'mb-0' : 'mb-3' }}">
                                    <div class="d-flex justify-content-between extra-small fw-bold text-dark mb-1">
                                        <span>{{ $candidate->member->full_name ?? 'Candidate #' . $candidate->id }}</span>
                                        <span>{{ $share }}% ({{ $candidate->votes_received }} {{ \Illuminate\Support\Str::plural('Vote', $candidate->votes_received) }})</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 50rem;">
                                        <div class="progress-bar {{ $loop->even ? 'bg-teal' : 'bg-primary' }}" role="progressbar" style="width: {{ $share }}%;{{ $loop->even ? ' background-color:#0d9488;' : '' }}" aria-valuenow="{{ $share }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($election->candidates->isEmpty())
                    <p class="text-muted small mb-0 py-4 text-center">Candidates for this election have not been announced yet. Please check back soon.</p>
                @else
                    <!-- Active Interactive Balloting Booth -->
                    <div x-data="{ selectedCandidate: null }">
                        <p class="extra-small text-muted mb-4">Please read candidate profiles below and select the candidate you wish to vote for. You can only vote for <strong>one</strong> candidate for the position of <strong>{{ $election->position }}</strong>.</p>

                        <form action="{{ route('membership.castVote') }}" method="POST">
                            @csrf
                            <input type="hidden" name="candidate_id" :value="selectedCandidate">

                            <div class="row g-4 justify-content-center mb-4">
                                @foreach($election->candidates as $candidate)
                                    <div class="col-md-5">
                                        <div @click="selectedCandidate = {{ $candidate->id }}" :class="selectedCandidate === {{ $candidate->id }} ? 'selected' : ''" class="candidate-voter-card">
                                            <div class="selected-badge"><i class="fa-solid fa-check"></i></div>
                                            <img src="{{ asset($candidate->member->photo ?? '/images/default-avatar-m.png') }}" class="candidate-voter-photo" alt="Candidate photo" onerror="this.src='/images/default-avatar-m.png'">
                                            <h5 class="fw-bold text-dark mb-1">{{ $candidate->member->full_name ?? 'Candidate #' . $candidate->id }}</h5>
                                            <span class="badge bg-primary rounded-pill px-2 py-1 extra-small mb-2">{{ $election->position }} Candidate</span>
                                            @if($candidate->member)
                                                <p class="extra-small text-muted mb-0"><i class="fa-solid fa-university me-1"></i> {{ $candidate->member->institution }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow" :disabled="!selectedCandidate">
                                    <i class="fa-solid fa-vote-yea me-1"></i> Cast Secret Ballot Vote
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4 text-center py-5">
                <div class="display-4 text-muted mb-3"><i class="fa-solid fa-box-archive"></i></div>
                <h5 class="fw-bold text-dark">No elections are open right now</h5>
                <p class="text-muted small mx-auto mb-0" style="max-width: 480px;">The electronic voting booth opens here when the Executive Council announces an election. Watch the student feed for the official schedule.</p>
            </div>
        @endforelse
    </div>


    <!-- ─── TAB 3: HOSTEL & PG FINDER DIRECTORY ─── -->
    <div x-show="activeTab === 'accommodation'" x-transition x-cloak>
        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
                <h5 class="fw-black text-dark mb-0"><i class="fa-solid fa-hotel text-primary me-2"></i> KSO Verified Hostels & PG Accommodations</h5>
                <span class="badge bg-teal text-white px-3 py-1 rounded-pill extra-small" style="background:#0d9488;"><i class="fa-solid fa-house-circle-check me-1"></i> Checked & Recommended</span>
            </div>

            <p class="extra-small text-muted mb-4">We are committed to helping newly arriving students find secure, affordable, and welcoming accommodation near their colleges in Chandigarh, Mohali, and Panchkula.</p>

            @if($accommodations->isEmpty())
                <div class="text-center py-5">
                    <div class="display-4 text-muted mb-3"><i class="fa-solid fa-house-circle-exclamation"></i></div>
                    <h6 class="fw-bold text-dark">No verified listings right now</h6>
                    <p class="text-muted extra-small mx-auto mb-0" style="max-width: 440px;">The accommodation desk verifies every hostel and PG before listing it here. New verified listings will appear as soon as they are approved.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($accommodations as $listing)
                        <div class="col-md-4">
                            <div class="accommodation-card h-100">
                                <div class="accommodation-img-wrapper">
                                    <img src="{{ asset($listing->photo ?? '/images/default-avatar-m.png') }}" alt="{{ $listing->name }}" onerror="this.src='/images/kso-logo.jpg'">
                                    <span class="accommodation-tag badge bg-success text-white">{{ $listing->type }}</span>
                                    <span class="accommodation-rent">₹{{ number_format($listing->rent_monthly) }}/mo</span>
                                </div>
                                <div class="p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-primary fw-bold extra-small">{{ $listing->location }}</small>
                                        @if($listing->landmark)
                                            <small class="text-muted extra-small"><i class="fa-solid fa-walking me-1"></i> {{ $listing->landmark }}</small>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">{{ $listing->name }}</h6>
                                    @if($listing->description)
                                        <p class="extra-small text-muted mb-3">{{ $listing->description }}</p>
                                    @endif
                                    <div class="d-flex gap-2">
                                        <a href="tel:{{ preg_replace('/[^+\d]/', '', $listing->contact_phone) }}" class="btn btn-sm btn-primary rounded-pill flex-grow-1 fw-bold"><i class="fa-solid fa-phone me-1"></i> Contact Owner</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


    <!-- ─── TAB 4: ACADEMIC & CAREER RESOURCES DESK ─── -->
    <div x-show="activeTab === 'academic'" x-transition x-cloak>
        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
                <h5 class="fw-black text-dark mb-0"><i class="fa-solid fa-book-open text-primary me-2"></i> KSO Student Academic Resources & Question Bank</h5>
                <span class="badge bg-primary-lt text-primary px-3 py-1 rounded-pill extra-small"><i class="fa-solid fa-graduation-cap me-1"></i> Free Download Portal</span>
            </div>

            <p class="extra-small text-muted mb-4">Access official university guidelines, scholarship instructions, syllabi, admission prospectuses, and previous years question papers compiled by our cultural and academic desks.</p>

            @if($resources->isEmpty())
                <div class="text-center py-5">
                    <div class="display-4 text-muted mb-3"><i class="fa-regular fa-folder-open"></i></div>
                    <h6 class="fw-bold text-dark">The resource library is being stocked</h6>
                    <p class="text-muted extra-small mx-auto mb-0" style="max-width: 440px;">Guides, prospectuses, and question banks uploaded by the academic desk will appear here for download.</p>
                </div>
            @else
                <div class="row">
                    @foreach($resources as $category => $items)
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-primary text-uppercase extra-small tracking-wider mb-3"><i class="fa-solid fa-circle-chevron-down me-1"></i> {{ $category }}</h6>

                            @foreach($items as $resource)
                                <div class="resource-list-item shadow-sm">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="resource-icon-box bg-primary-lt text-primary">
                                            <i class="fa-regular {{ str_ends_with($resource->file_path, '.zip') ? 'fa-file-zipper' : 'fa-file-pdf' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark extra-small">{{ $resource->title }}</div>
                                            <small class="text-muted extra-small">{{ strtoupper(pathinfo($resource->file_path, PATHINFO_EXTENSION)) }} File • {{ $resource->humanFileSize() }} • {{ $resource->download_count }} downloads</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('membership.downloadResource', $resource->id) }}" class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" aria-label="Download {{ $resource->title }}"><i class="fa-solid fa-download"></i></a>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Medical Relief Claim Modal -->
<div class="modal fade" id="medicalClaimModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-danger">New Medical Relief Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('membership.submitMedicalClaim') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Patient Name</label>
                        <input type="text" name="patient_name" class="form-control" required placeholder="Full Name of Patient">
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Hospital Name</label>
                        <input type="text" name="hospital_name" class="form-control" required placeholder="e.g. PGIMER Sector 12">
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Nature of Illness / Emergency</label>
                        <textarea name="nature_of_illness" class="form-control" rows="2" required placeholder="Describe the medical situation..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Amount Requested (₹)</label>
                        <input type="number" name="amount_requested" class="form-control" required min="1" placeholder="Estimated assistance needed">
                    </div>
                    <div class="mb-0">
                        <label class="form-label extra-small fw-bold">Support Document (Prescription/Bill)</label>
                        <input type="file" name="medical_document" class="form-control">
                        <div class="form-text extra-small">Max size 5MB (JPG, PNG, PDF)</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
