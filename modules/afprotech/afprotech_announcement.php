<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AFPROTECH</title>
    <link rel="icon" href="../../assets/logo/afprotech_2.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/app.css">

</head>
<body class="theme-afprotech">

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-header-content">
                <div class="logo-container">
                    <img src="../../assets/logo/afprotech_2.png" alt="AFPROTECH Logo">
                    <h4>Association of Food Processing Technology Students</h4>
                </div>
                <button class="btn-close-sidebar" id="closeSidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="afprotech_dashboard.php">
                        <i class="bi bi-house-door"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="afprotech_announcement.php">
                        <i class="bi bi-megaphone"></i>
                        <span>Announcement</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="afprotech_event.php">
                        <i class="bi bi-calendar-event"></i>
                        <span>Event</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-wrench-adjustable"></i>
                        <span>Services</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../dashboard.php">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <button class="menu-toggle" id="menuToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="search-box">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="user-info">
                <div class="notifications">
                    <i class="bi bi-bell fs-5"></i>
                </div>
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">Tim</div>
                    <div class="user-role">Student</div>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area">
            <h2 class="mb-4">Announcement</h2>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Announcements</h5>
                                <button id="btnNew" class="btn btn-primary">+ New Announcement</button>
                        </div>

                        <div id="announcementsContainer">
                                <!-- announcements will be injected here -->
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title">Create New Announcement</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="announcementForm">
                                            <input type="hidden" name="id" id="annId" value="">
                                            <div class="mb-3">
                                                <label class="form-label">Title *</label>
                                                <input type="text" class="form-control" id="annTitle" name="title" maxlength="255" placeholder="Enter announcement title">
                                                <div class="form-text">Maximum 255 characters</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Content *</label>
                                                <textarea class="form-control" id="annContent" name="content" rows="6" placeholder="Enter announcement content..."></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Date & Time</label>
                                                <input type="datetime-local" class="form-control" id="annDatetime" name="datetime">
                                                <div class="form-text">Leave empty for current date and time</div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" id="saveAnn" class="btn btn-primary">Save Announcement</button>
                                    </div>
                                </div>
                            </div>
                        </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const closeSidebar = document.getElementById('closeSidebar');
            
            // Toggle sidebar on menu button click
            menuToggle.addEventListener('click', function() {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
            });
            
            // Close sidebar methods:
            
            // 1. Close button click
            closeSidebar.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
            
            // 2. Overlay click
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });

            // 3. Auto-close when clicking menu links
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                    }
                });
            });
            
            // 4. Window resize (close on desktop)
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });
    </script>
    <script>
        // Announcement CRUD
        const apiUrl = 'afprotech_announcement_actions.php';
        const announcementsContainer = document.getElementById('announcementsContainer');
        const btnNew = document.getElementById('btnNew');
        const annModalEl = document.getElementById('announcementModal');
        const annModal = new bootstrap.Modal(annModalEl);
        const annForm = document.getElementById('announcementForm');
        const saveBtn = document.getElementById('saveAnn');

        btnNew.addEventListener('click', () => {
            openModal();
        });

        function openModal(data = null) {
            annForm.reset();
            document.getElementById('annId').value = data ? data.announcement_id : '';
            document.getElementById('annTitle').value = data ? data.announcement_title : '';
            document.getElementById('annContent').value = data ? data.announcement_content : '';
            if (data && data.announcement_datetime) {
                // convert to datetime-local (YYYY-MM-DDTHH:MM)
                const dt = new Date(data.announcement_datetime);
                const iso = dt.toISOString();
                document.getElementById('annDatetime').value = iso.substring(0,16);
            } else {
                document.getElementById('annDatetime').value = '';
            }
            annModal.show();
        }

        async function loadAnnouncements() {
            const res = await fetch(apiUrl + '?action=list');
            const json = await res.json();
            if (json.success) renderAnnouncements(json.data);
        }

        function renderAnnouncements(items) {
            if (!items || items.length === 0) {
                announcementsContainer.innerHTML = `<div class="card"><div class="card-body text-center py-5 text-muted"><i class="bi bi-megaphone fs-1"></i><div>No announcements yet. Create your first announcement!</div></div></div>`;
                return;
            }
            let out = '';
            items.forEach(it => {
                const dt = new Date(it.announcement_datetime);
                const dtStr = dt.toLocaleString();
                out += `
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-1">${escapeHtml(it.announcement_title)}</h5>
                                <div class="text-muted small">${dtStr}</div>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary me-2" data-id="${it.announcement_id}" data-action="edit">Edit</button>
                                <button class="btn btn-sm btn-outline-danger" data-id="${it.announcement_id}" data-action="delete">Delete</button>
                            </div>
                        </div>
                        <p class="card-text mt-3">${nl2br(escapeHtml(it.announcement_content))}</p>
                    </div>
                </div>`;
            });
            announcementsContainer.innerHTML = out;

            // attach handlers
            announcementsContainer.querySelectorAll('button[data-action="edit"]').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const id = e.currentTarget.getAttribute('data-id');
                    // find item from rendered list
                    const res = await fetch(apiUrl + '?action=list');
                    const json = await res.json();
                    const item = json.data.find(x => x.announcement_id == id);
                    if (item) openModal(item);
                });
            });
            announcementsContainer.querySelectorAll('button[data-action="delete"]').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const id = e.currentTarget.getAttribute('data-id');
                    if (!confirm('Delete this announcement?')) return;
                    const form = new FormData();
                    form.append('id', id);
                    const res = await fetch(apiUrl + '?action=delete', { method: 'POST', body: form });
                    const json = await res.json();
                    if (json.success) loadAnnouncements();
                    else alert(json.message || 'Failed');
                });
            });
        }

        saveBtn.addEventListener('click', async () => {
            const formData = new FormData(annForm);
            const id = document.getElementById('annId').value;
            const action = id ? 'update' : 'create';
            const res = await fetch(apiUrl + '?action=' + action, { method: 'POST', body: formData });
            const json = await res.json();
            if (json.success) {
                annModal.hide();
                loadAnnouncements();
            } else {
                alert(json.message || 'Save failed');
            }
        });

        function escapeHtml(s) {
            return (s+'').replace(/[&<>\"]+/g, function(match){
                return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[match];
            });
        }

        function nl2br(str) {
            return str.replace(/\n/g, '<br>');
        }

        // initial load
        loadAnnouncements();
    </script>
</body>
</html>