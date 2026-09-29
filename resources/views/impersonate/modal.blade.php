@php
    $impersonateCompanies = \App\Models\User::where('type', 'company')->orderBy('name', 'asc')->get(['id', 'name', 'email']);
    $impersonateCounts = [
        'all'      => \App\Models\User::where('type', '!=', 'super admin')->count(),
        'company'  => \App\Models\User::where('type', 'company')->count(),
        'user'     => \App\Models\User::whereNotIn('type', ['super admin', 'company', 'employee'])->count(),
        'employee' => \App\Models\User::where('type', 'employee')->count(),
    ];
@endphp

<!-- Impersonate User Popup Modal -->
<div class="modal fade" id="impersonateModal" tabindex="-1" aria-labelledby="impersonateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content impersonate-modal-container border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Modal Header --}}
            <div class="modal-header border-0 py-3 px-4 flex-shrink-0" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 text-primary d-flex align-items-center justify-content-center shadow-xs" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.9);">
                        <i class="ti ti-user-check fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0" id="impersonateModalLabel">
                            {{ __('Impersonate User') }}
                        </h5>
                        <small class="text-white-50 fs-8">{{ __('Search and securely log in as any Company, Sub-User, or Employee account.') }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Filters Section (Fixed at top of modal body) --}}
            <div class="impersonate-filter-section bg-light border-bottom flex-shrink-0">
                {{-- Role Filter Tabs --}}
                <div class="impersonate-tabs-wrapper" id="impersonateTypeTabs">
                    <button type="button" class="btn btn-sm btn-primary impersonate-tab-btn active" data-type="all">
                        <i class="ti ti-users"></i>
                        <span>{{ __('All Accounts') }}</span>
                        <span class="badge bg-white text-primary rounded-pill" id="badgeAllCount">{{ number_format($impersonateCounts['all']) }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary impersonate-tab-btn" data-type="company">
                        <i class="ti ti-building"></i>
                        <span>{{ __('Companies') }}</span>
                        <span class="badge bg-secondary text-white rounded-pill" id="badgeCompCount">{{ number_format($impersonateCounts['company']) }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary impersonate-tab-btn" data-type="user">
                        <i class="ti ti-user-star"></i>
                        <span>{{ __('Sub-Users & HR') }}</span>
                        <span class="badge bg-secondary text-white rounded-pill" id="badgeUserCount">{{ number_format($impersonateCounts['user']) }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary impersonate-tab-btn" data-type="employee">
                        <i class="ti ti-id-badge-2"></i>
                        <span>{{ __('Employees') }}</span>
                        <span class="badge bg-secondary text-white rounded-pill" id="badgeEmpCount">{{ number_format($impersonateCounts['employee']) }}</span>
                    </button>
                </div>

                {{-- Search & Company Filters --}}
                <div class="row g-2 align-items-center impersonate-search-row">
                    <div class="col-md-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 px-3"><i class="ti ti-search text-muted"></i></span>
                            <input type="text" id="modalImpersonateSearchInput" class="form-control border-start-0 impersonate-search-input" placeholder="{{ __('Search by user name or email address...') }}" autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="modalImpersonateClearBtn" style="display:none;">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <select id="modalImpersonateCompanyFilter" class="form-select form-select-sm impersonate-company-select">
                            <option value="">{{ __('Filter by All Companies') }}</option>
                            @foreach($impersonateCompanies as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Modal Body (Full Height Scrollable List) --}}
            <div class="modal-body p-0 d-flex flex-column flex-grow-1" style="overflow: hidden;">
                <div id="modalImpersonateResults" class="p-3 flex-grow-1" style="overflow-y: auto;">
                    {{-- Loading Spinner --}}
                    <div class="text-center py-5 text-muted" id="modalImpersonateLoading" style="display: none;">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                        <p class="fs-7 mb-0">{{ __('Loading accounts...') }}</p>
                    </div>

                    {{-- Dynamic User Rows Container --}}
                    <div id="modalImpersonateList"></div>

                    {{-- Load More Container --}}
                    <div id="modalImpersonateLoadMoreWrapper" class="text-center py-3" style="display: none;">
                        <button type="button" id="modalImpersonateLoadMoreBtn" class="btn btn-sm btn-outline-primary rounded-pill px-4 shadow-xs">
                            <span id="loadMoreBtnText"><i class="ti ti-arrow-down me-1"></i> {{ __('Load More Accounts') }}</span>
                            <span id="loadMoreBtnSpinner" class="spinner-border spinner-border-sm ms-1" style="display: none;" role="status"></span>
                        </button>
                        <small class="text-muted d-block mt-1 fs-8" id="modalImpersonateShowingText"></small>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer bg-light border-top py-2.5 px-4 d-flex justify-content-between flex-shrink-0">
                <small class="text-muted fs-8 d-flex align-items-center gap-1">
                    <i class="ti ti-shield-lock text-primary"></i>
                    <span>{{ __('Original Super Admin session is safely restored upon exiting impersonation.') }}</span>
                </small>
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
    .impersonate-modal-container {
        height: 85vh;
        max-height: 760px;
        min-height: 520px;
        display: flex;
        flex-direction: column;
    }

    .impersonate-filter-section {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px 18px 20px;
    }

    .impersonate-tabs-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px !important;
        align-items: center;
        clear: both;
    }

    .impersonate-tab-btn {
        height: 36px;
        padding: 6px 14px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        border-radius: 50px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        line-height: 1 !important;
        transition: all 0.2s ease-in-out;
        box-shadow: none !important;
        margin: 0 !important;
    }

    .impersonate-tab-btn:hover {
        transform: translateY(-1px);
    }

    .impersonate-search-row {
        margin-top: 0 !important;
        clear: both;
    }

    .impersonate-search-input,
    .impersonate-company-select {
        height: 38px !important;
        font-size: 13px !important;
    }

    .impersonate-result-row {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .impersonate-result-row:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .impersonate-modal-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        background: #eef2ff;
        color: #0d6efd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        border: 1px solid #e0e7ff;
        flex-shrink: 0;
    }

    .role-badge-company {
        background: #e0f2fe;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }

    .role-badge-employee {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .role-badge-user {
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .impersonate-action-btn {
        background: #0d6efd;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        transition: all 0.15s ease-in-out;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.2);
        text-decoration: none !important;
        white-space: nowrap;
    }

    .impersonate-action-btn:hover {
        background: #0b5ed7;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.35);
    }

    /* Scrollbar styling for modal results */
    #modalImpersonateResults::-webkit-scrollbar {
        width: 6px;
    }
    #modalImpersonateResults::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    #modalImpersonateResults::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    #modalImpersonateResults::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let activeUserType = 'all';
        let currentPage = 1;
        let totalCount = 0;
        let loadedCount = 0;
        let hasMore = false;
        let isLoading = false;
        let searchTimeout = null;

        const searchInput = document.getElementById('modalImpersonateSearchInput');
        const companyFilter = document.getElementById('modalImpersonateCompanyFilter');
        const clearBtn = document.getElementById('modalImpersonateClearBtn');
        const resultsContainer = document.getElementById('modalImpersonateList');
        const resultsWrapper = document.getElementById('modalImpersonateResults');
        const loadingIndicator = document.getElementById('modalImpersonateLoading');
        const loadMoreWrapper = document.getElementById('modalImpersonateLoadMoreWrapper');
        const loadMoreBtn = document.getElementById('modalImpersonateLoadMoreBtn');
        const loadMoreBtnText = document.getElementById('loadMoreBtnText');
        const loadMoreBtnSpinner = document.getElementById('loadMoreBtnSpinner');
        const showingText = document.getElementById('modalImpersonateShowingText');
        const typeTabs = document.querySelectorAll('#impersonateTypeTabs button');
        const modalElement = document.getElementById('impersonateModal');

        if (!modalElement) return;

        // Fetch users via AJAX
        function fetchImpersonateUsers(page = 1, append = false) {
            if (isLoading) return;
            isLoading = true;

            const query = searchInput.value.trim();
            const companyId = companyFilter.value;

            if (!append) {
                loadingIndicator.style.display = 'block';
                resultsContainer.innerHTML = '';
                loadMoreWrapper.style.display = 'none';
                currentPage = 1;
                loadedCount = 0;
            } else {
                loadMoreBtnText.style.display = 'none';
                loadMoreBtnSpinner.style.display = 'inline-block';
            }

            const params = new URLSearchParams({
                user_type: activeUserType,
                search: query,
                company_id: companyId,
                page: page,
                limit: 25
            });

            fetch(`{{ route('impersonate.search') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                isLoading = false;
                loadingIndicator.style.display = 'none';
                loadMoreBtnText.style.display = 'inline';
                loadMoreBtnSpinner.style.display = 'none';

                if (!data.success || !data.users || data.users.length === 0) {
                    if (!append) {
                        resultsContainer.innerHTML = `
                            <div class="text-center py-5 text-muted">
                                <i class="ti ti-user-off fs-1 d-block mb-2 text-muted opacity-50"></i>
                                <h6 class="fw-bold mb-1 text-dark">{{ __('No accounts found') }}</h6>
                                <p class="fs-8 text-muted mb-0">{{ __('Try adjusting your search criteria or role filters.') }}</p>
                            </div>
                        `;
                        loadMoreWrapper.style.display = 'none';
                    }
                    return;
                }

                totalCount = data.total || 0;
                hasMore = data.has_more || false;
                currentPage = data.current_page || page;
                loadedCount += data.users.length;

                let html = '';
                data.users.forEach(u => {
                    let roleBadgeClass = 'role-badge-user';
                    let roleIcon = 'ti-user-check';
                    let roleLabel = u.type.charAt(0).toUpperCase() + u.type.slice(1);

                    if (u.type === 'company') {
                        roleBadgeClass = 'role-badge-company';
                        roleIcon = 'ti-building';
                        roleLabel = '{{ __("Company") }}';
                    } else if (u.type === 'employee') {
                        roleBadgeClass = 'role-badge-employee';
                        roleIcon = 'ti-id-badge';
                        roleLabel = '{{ __("Employee") }}';
                    }

                    const avatarHtml = u.avatar
                        ? `<img src="${u.avatar}" alt="${u.name}" class="impersonate-modal-avatar">`
                        : `<div class="impersonate-modal-avatar">${u.initials}</div>`;

                    html += `
                        <div class="impersonate-result-row">
                            <div class="d-flex align-items-center gap-3">
                                ${avatarHtml}
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-0.5">
                                        <strong class="text-dark fs-7">${u.name}</strong>
                                        <span class="badge ${roleBadgeClass} px-2 py-0.5 rounded-pill fs-8">
                                            <i class="ti ${roleIcon} me-1"></i>${roleLabel}
                                        </span>
                                        ${u.employee_code ? `<small class="badge bg-light text-muted border px-1.5 py-0.5 fs-8">${u.employee_code}</small>` : ''}
                                    </div>
                                    <div class="text-muted fs-8 d-flex flex-wrap align-items-center gap-2">
                                        <span><i class="ti ti-mail me-1"></i>${u.email}</span>
                                        <span>&bull;</span>
                                        <span><i class="ti ti-building-community me-1"></i>${u.parent_company}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end ps-2">
                                <a href="${u.impersonate_url}" class="impersonate-action-btn">
                                    <i class="ti ti-login fs-6"></i>
                                    <span>{{ __('Impersonate') }}</span>
                                </a>
                            </div>
                        </div>
                    `;
                });

                if (append) {
                    resultsContainer.insertAdjacentHTML('beforeend', html);
                } else {
                    resultsContainer.innerHTML = html;
                }

                // Manage Load More display
                if (hasMore) {
                    loadMoreWrapper.style.display = 'block';
                    showingText.innerText = `Showing ${loadedCount} of ${totalCount.toLocaleString()} accounts`;
                } else {
                    if (totalCount > 25) {
                        loadMoreWrapper.style.display = 'block';
                        loadMoreBtn.style.display = 'none';
                        showingText.innerText = `All ${totalCount.toLocaleString()} accounts loaded.`;
                    } else {
                        loadMoreWrapper.style.display = 'none';
                    }
                }
            })
            .catch(err => {
                isLoading = false;
                loadingIndicator.style.display = 'none';
                loadMoreBtnText.style.display = 'inline';
                loadMoreBtnSpinner.style.display = 'none';

                if (!append) {
                    resultsContainer.innerHTML = `
                        <div class="text-center py-4 text-danger fs-8">
                            <i class="ti ti-alert-triangle fs-3 d-block mb-1"></i>
                            {{ __('Failed to load accounts. Please try again.') }}
                        </div>
                    `;
                }
                console.error(err);
            });
        }

        // Trigger on modal open
        modalElement.addEventListener('shown.bs.modal', function () {
            searchInput.focus();
            fetchImpersonateUsers(1, false);
        });

        // Tab click filters
        typeTabs.forEach(tab => {
            tab.addEventListener('click', function () {
                typeTabs.forEach(t => {
                    t.classList.remove('btn-primary', 'active');
                    t.classList.add('btn-outline-secondary');
                    const badge = t.querySelector('.badge');
                    if (badge) {
                        badge.classList.remove('bg-white', 'text-primary');
                        badge.classList.add('bg-secondary', 'text-white');
                    }
                });

                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-primary', 'active');
                const activeBadge = this.querySelector('.badge');
                if (activeBadge) {
                    activeBadge.classList.remove('bg-secondary', 'text-white');
                    activeBadge.classList.add('bg-white', 'text-primary');
                }

                activeUserType = this.getAttribute('data-type');
                loadMoreBtn.style.display = 'inline-block';
                fetchImpersonateUsers(1, false);
            });
        });

        // Search input with debounce
        searchInput.addEventListener('input', function () {
            clearBtn.style.display = this.value.trim() ? 'block' : 'none';
            loadMoreBtn.style.display = 'inline-block';
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                fetchImpersonateUsers(1, false);
            }, 250);
        });

        // Clear search
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            searchInput.focus();
            loadMoreBtn.style.display = 'inline-block';
            fetchImpersonateUsers(1, false);
        });

        // Company filter dropdown
        companyFilter.addEventListener('change', function () {
            loadMoreBtn.style.display = 'inline-block';
            fetchImpersonateUsers(1, false);
        });

        // Load More button click
        loadMoreBtn.addEventListener('click', function () {
            if (hasMore && !isLoading) {
                fetchImpersonateUsers(currentPage + 1, true);
            }
        });

        // Optional Infinite Scroll on scroll near bottom
        resultsWrapper.addEventListener('scroll', function () {
            if (hasMore && !isLoading) {
                const scrollPos = resultsWrapper.scrollTop + resultsWrapper.clientHeight;
                const scrollHeight = resultsWrapper.scrollHeight;
                if (scrollPos >= scrollHeight - 60) {
                    fetchImpersonateUsers(currentPage + 1, true);
                }
            }
        });
    });
</script>
