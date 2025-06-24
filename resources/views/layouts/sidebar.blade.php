<!-- left-sidebar -->
<div class="left-sidebar">
    <!-- LOGO -->
    <div class="brand">
        <a href="index.html" class="logo">
            <span>
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span>
                <img src="{{ asset('assets/images/logo.png') }}" alt="logo-large" class="logo-lg logo-light">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark">
            </span>
        </a>
    </div>
    <!--end logo-->
    <div class="menu-content h-100" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px 0px -60px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: -20px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: 100%; padding-right: 20px; padding-bottom: 0px; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px 0px 60px;">
        <div class="menu-body navbar-vertical tab-content">
            <div class="collapse navbar-collapse" id="sidebarCollapse">
                <!-- Navigation -->
                <ul class="navbar-nav">
                    <li class="menu-label mt-0">W<span>EBSITE</span></li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('banner.*') ? 'active' : '' }}" href="{{ route('banner.index') }}"><i class="ti ti-photo menu-icon"></i><span>Banner</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('collection.*') ? 'active' : '' }}" href="{{ route('collection.index') }}"><i class="ti ti-archive menu-icon"></i><span>Collection</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('press.*') ? 'active' : '' }}" href="{{ route('press.index') }}"><i class="ti ti-news menu-icon"></i><span>Press</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('achievement.*') ? 'active' : '' }}" href="{{ route('achievement.index') }}"><i class="ti ti-trophy menu-icon"></i><span>Achievement</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('about-company.*') ? 'active' : '' }}" href="{{ route('about-company.index') }}"><i class="ti ti-building menu-icon"></i><span>About Company</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('about.*') ? 'active' : '' }}" href="{{ route('about.index') }}"><i class="ti ti-user menu-icon"></i><span>About</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}"><i class="ti ti-phone menu-icon"></i><span>Contact</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}"><i class="ti ti-settings menu-icon"></i><span>Settings</span></a>
                    </li><!--end nav-item-->

                    <li class="menu-label mt-0">S<span>HOP</span></li>

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('shop.banner.*') ? 'active' : '' }}" href="{{ route('shop.banner.index') }}"><i class="ti ti-photo menu-icon"></i><span>Shop Banner</span></a>
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('shop.fashion-week-banner.*') ? 'active' : '' }}" href="{{ route('shop.fashion-week-banner.index') }}"><i class="ti ti-photo menu-icon"></i><span>Fashion Week Banner</span></a>
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#category-sidebar" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="category-sidebar">
                            <i class="ti ti-stack menu-icon"></i>
                            <span>Category</span>
                        </a>
                        <div class="collapse {{ Route::is('main-category.*') || Route::is('product-category.*') ? 'show' : '' }}" id="category-sidebar">
                            <ul class="nav flex-column">
                                <li class="nav-item {{ Route::is('main-category.*') ? 'active' : '' }}">
                                    <a href="{{ route('main-category.index') }}" class="nav-link ">Main Category</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a href="{{ route('product-category.index') }}" class="nav-link ">Product Category</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarAnalytics-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('product.*') ? 'active' : '' }}" href="{{ route('product.index') }}"><i class="ti ti-box menu-icon"></i><span>Products</span></a>
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('vouchers.*') ? 'active' : '' }}" href="{{ route('vouchers.index') }}"><i class="ti ti-ticket menu-icon"></i><span>Vouchers</span></a>
                    </li><!--end nav-item-->
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="ti ti-users menu-icon"></i><span>Users</span></a>
                    </li><!--end nav-item-->
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}"><i class="ti ti-shopping-cart menu-icon"></i><span>Orders</span></a>
                    </li><!--end nav-item-->

                    {{-- <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarCrypto" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCrypto">
                            <i class="ti ti-currency-bitcoin menu-icon"></i>
                            <span>Crypto</span>
                        </a>
                        <div class="collapse " id="sidebarCrypto">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="crypto-exchange.html">Exchange</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="crypto-wallet.html">Wallet</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="crypto-news.html">Crypto News</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="crypto-ico.html">ICO List</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="crypto-settings.html">Settings</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarCrypto-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarCRM" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCRM">
                            <i class="ti ti-ball-football menu-icon"></i>
                            <span>CRM</span>
                        </a>
                        <div class="collapse " id="sidebarCRM">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="crm-contacts.html">Contacts</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="crm-opportunities.html">Opportunities</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="crm-leads.html">Leads</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="crm-customers.html">Customers</a>
                                </li><!--end nav-item--> 
                            </ul><!--end nav-->
                        </div><!--end sidebarCRM-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarProjects" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProjects">
                            <i class="ti ti-brand-asana menu-icon"></i>
                            <span>Projects</span>
                        </a>
                        <div class="collapse " id="sidebarProjects">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="projects-clients.html">Clients</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="projects-team.html">Team</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="projects-project.html">Project</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="projects-task.html">Task</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="projects-kanban-board.html">Kanban Board</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="projects-chat.html">Chat</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="projects-users.html">Users</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="projects-create.html">Project Create</a>
                                </li><!--end nav-item--> 
                            </ul><!--end nav-->
                        </div><!--end sidebarProjects-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarEcommerce" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEcommerce">
                            <i class="ti ti-shopping-cart menu-icon"></i>
                            <span>Ecommerce</span>
                        </a>
                        <div class="collapse " id="sidebarEcommerce">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="ecommerce-products.html">Products</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ecommerce-product-list.html">Product List</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ecommerce-product-detail.html">Product Detail</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ecommerce-cart.html">Cart</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ecommerce-checkout.html">Checkout</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarEcommerce-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarHelpdesk" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarHelpdesk">
                            <i class="ti ti-headset menu-icon"></i>
                            <span>Helpdesk</span>
                        </a>
                        <div class="collapse " id="sidebarHelpdesk">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="helpdesk-teckets.html">Tickets</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="helpdesk-reports.html">Reports</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="helpdesk-agents.html">Agents</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarHelpdesk-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarHospital" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarHospital" id="hospital">
                            <i class="ti ti-building-hospital menu-icon"></i>
                            <span>Hospital</span>
                        </a>
                        <div class="collapse " id="sidebarHospital">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#sidebarAppointments " class="nav-link collapsed" data-bs-toggle="collapse" id="appointments" role="button" aria-expanded="false" aria-controls="sidebarAppointments" aria-labelledby="hospital">
                                        Appointments 
                                    </a>
                                    <div class="collapse " id="sidebarAppointments">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" aria-labelledby="appointments" href="hospital-doctor-shedule.html">Dr. Shedule</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-all-appointments.html">All Appointments</a>
                                            </li><!--end nav-item-->
                                        </ul><!--end nav-->
                                    </div><!--end sidebarAppointments-->
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a href="#sidebarDoctors" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDoctors">
                                        Doctors
                                    </a>
                                    <div class="collapse" id="sidebarDoctors">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-all-doctors.html">All Doctors</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-add-doctor.html">Add Doctor</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-doctor-edit.html">Doctor Edit</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-doctor-profile.html">Doctor Profile</a>
                                            </li><!--end nav-item-->
                                        </ul><!--end nav-->
                                    </div><!--end sidebarDoctors-->
                                </li><!--end nav-item-->

                                <li class="nav-item">
                                    <a href="#sidebarPatients" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPatients">
                                        Patients
                                    </a>
                                    <div class="collapse" id="sidebarPatients">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-all-patients.html">All Patients</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-add-patient.html">Add Patient</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-patient-edit.html">Patient Edit</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-patient-profile.html">Patient Profile</a>
                                            </li><!--end nav-item-->
                                        </ul><!--end nav-->
                                    </div><!--end sidebarPatients-->
                                </li><!--end nav-item-->

                                <li class="nav-item">
                                    <a href="#sidebarPayments" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPayments">
                                        Payments
                                    </a>
                                    <div class="collapse" id="sidebarPayments">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-all-payments.html">All Payments</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-payment-invoice.html">Payment Invoice</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-cashless-payments.html">Cashless Payments</a>
                                            </li><!--end nav-item-->
                                        </ul><!--end nav-->
                                    </div><!--end sidebarPayments-->
                                </li><!--end nav-item-->

                                <li class="nav-item">
                                    <a href="#sidebarStaff" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarStaff">
                                        Staff
                                    </a>
                                    <div class="collapse" id="sidebarStaff">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-all-staff.html">All Staff</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-add-member.html">Add Member</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-edit-member.html">Edit Member</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-member-profile.html">Member Profile</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-salary.html">Staff Salary</a>
                                            </li><!--end nav-item-->
                                        </ul><!--end nav-->
                                    </div><!--end sidebarStaff-->
                                </li><!--end nav-item-->

                                <li class="nav-item">
                                    <a href="#sidebarGeneral" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarGeneral">
                                        General
                                    </a>
                                    <div class="collapse" id="sidebarGeneral">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-all-rooms.html">Room Allotments</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-expenses.html">Expenses Report</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-departments.html">Departments</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-insurance-company.html">Insurance Co.</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-events.html">Events</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-leaves.html">Leaves</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-holidays.html">Holidays</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-attendance.html">Attendance</a>
                                            </li><!--end nav-item-->
                                            <li class="nav-item">
                                                <a class="nav-link" href="hospital-chat.html">Chat</a>
                                            </li><!--end nav-item-->
                                        </ul><!--end nav-->
                                    </div><!--end sidebarGeneral-->
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarHospital-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarEmail" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmail">
                            <i class="ti ti-mail menu-icon"></i>
                            <span>Email</span>
                        </a>
                        <div class="collapse " id="sidebarEmail">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="apps-email-inbox.html">Inbox</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="apps-email-read.html">Read Email</a>
                                </li><!--end nav-item--> 
                            </ul><!--end nav-->
                        </div><!--end sidebarEmail-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link" href="apps-chat.html"><i class="ti ti-brand-hipchat menu-icon"></i><span>Chat</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="apps-contact-list.html"><i class="ti ti-headphones menu-icon"></i><span>Contact List</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="apps-calendar.html"><i class="ti ti-calendar menu-icon"></i><span>Calendar</span></a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="apps-invoice.html"><i class="ti ti-file-invoice menu-icon"></i><span>Invoice</span></a>
                    </li><!--end nav-item-->
                    <li class="menu-label mt-0">C<span>omponents</span></li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarElements" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarElements">
                            <i class="ti ti-planet menu-icon"></i>
                        <span>UI Elements</span>
                        </a>
                        <div class="collapse " id="sidebarElements">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-alerts.html">Alerts</a>
                                </li><!--end nav-item--> 
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-avatar.html">Avatar</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-buttons.html">Buttons</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-badges.html">Badges</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-cards.html">Cards</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-carousels.html">Carousels</a>
                                </li><!--end nav-item-->                                
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-dropdowns.html">Dropdowns</a>
                                </li><!--end nav-item-->                                   
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-grids.html">Grids</a>
                                </li><!--end nav-item-->                                
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-images.html">Images</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-list.html">List</a>
                                </li><!--end nav-item-->                                   
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-modals.html">Modals</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-navs.html">Navs</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-navbar.html">Navbar</a>
                                </li><!--end nav-item--> 
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-paginations.html">Paginations</a>
                                </li><!--end nav-item-->   
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-popover-tooltips.html">Popover &amp; Tooltips</a>
                                </li><!--end nav-item-->                                
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-progress.html">Progress</a>
                                </li><!--end nav-item-->                                
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-spinners.html">Spinners</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-tabs-accordions.html">Tabs &amp; Accordions</a>
                                </li><!--end nav-item-->                               
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-typography.html">Typography</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="ui-videos.html">Videos</a>
                                </li><!--end nav-item--> 
                            </ul><!--end nav-->
                        </div><!--end sidebarElements-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarAdvancedUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvancedUI">
                            <i class="ti ti-chart-bubble menu-icon"></i>
                            <span>Advanced UI</span>
                        </a>
                        <div class="collapse " id="sidebarAdvancedUI">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-animation.html">Animation</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-clipboard.html">Clip Board</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-dragula.html">Dragula</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-files.html">File Manager</a>
                                </li><!--end nav-item--> 
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-highlight.html">Highlight</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-rangeslider.html">Range Slider</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-ratings.html">Ratings</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-ribbons.html">Ribbons</a>
                                </li><!--end nav-item-->                                  
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-sweetalerts.html">Sweet Alerts</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="advanced-toasts.html">Toasts</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarAdvancedUI-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarForms" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarForms">
                            <i class="ti ti-forms menu-icon"></i>
                            <span>Forms</span>
                        </a>
                        <div class="collapse " id="sidebarForms">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="forms-elements.html">Basic Elements</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="forms-advanced.html">Advance Elements</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="forms-validation.html">Validation</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="forms-wizard.html">Wizard</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="forms-editors.html">Editors</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="forms-uploads.html">File Upload</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="forms-img-crop.html">Image Crop</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarForms-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarCharts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCharts">
                            <i class="ti ti-chart-donut menu-icon"></i>
                        <span>Charts</span>
                        </a>
                        <div class="collapse " id="sidebarCharts">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="charts-apex.html">Apex</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="charts-justgage.html">JustGage</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="charts-chartjs.html">Chartjs</a>
                                </li><!--end nav-item--> 
                                <li class="nav-item">
                                    <a class="nav-link" href="charts-toast-ui.html">Toast</a>
                                </li><!--end nav-item--> 
                            </ul><!--end nav-->
                        </div><!--end sidebarCharts-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarTables" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTables">
                            <i class="ti ti-table menu-icon"></i>
                            <span>Tables</span>
                        </a>
                        <div class="collapse " id="sidebarTables">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="tables-basic.html">Basic</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="tables-datatable.html">Datatables</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="tables-editable.html">Editable</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarTables-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarIcons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarIcons">
                            <i class="ti ti-parachute menu-icon"></i>
                        <span>Icons</span>
                        </a>
                        <div class="collapse " id="sidebarIcons">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="icons-materialdesign.html">Material Design</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="icons-fontawesome.html">Font awesome</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="icons-tabler.html">Tabler</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="icons-feather.html">Feather</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarIcons-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarMaps" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMaps">
                            <i class="ti ti-map menu-icon"></i>
                            <span>Maps</span>
                        </a>
                        <div class="collapse " id="sidebarMaps">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="maps-google.html">Google Maps</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="maps-leaflet.html">Leaflet Maps</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="maps-vector.html">Vector Maps</a>
                                </li><!--end nav-item--> 
                            </ul><!--end nav-->
                        </div><!--end sidebarMaps-->
                    </li><!--end nav-item-->

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarEmailTemplates" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmailTemplates">
                            <i class="ti ti-target menu-icon"></i>
                            <span>Email Templates</span>
                        </a>
                        <div class="collapse " id="sidebarEmailTemplates">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="email-templates-basic.html">Basic Action Email</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="email-templates-alert.html">Alert Email</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="email-templates-billing.html">Billing Email</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarEmailTemplates-->
                    </li><!--end nav-item-->
                    <li class="menu-label mt-0">C<span>rafted</span></li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPages">
                            <i class="ti ti-file-diff menu-icon"></i>
                            <span>Pages</span>
                        </a>
                        <div class="collapse " id="sidebarPages">
                            <ul class="nav flex-column">
                               <li class="nav-item">
                                    <a class="nav-link" href="pages-profile.html">Profile</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="pages-tour.html">Tour</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="pages-timeline.html">Timeline</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="pages-treeview.html">Treeview</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="pages-starter.html">Starter Page</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="pages-pricing.html">Pricing</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="pages-blogs.html">Blogs</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="pages-faq.html">FAQs</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="pages-gallery.html">Gallery</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarPages-->
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#sidebarAuthentication" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuthentication">
                            <i class="ti ti-shield-lock menu-icon"></i>
                            <span>Authentication</span>
                        </a>
                        <div class="collapse " id="sidebarAuthentication">
                            <ul class="nav flex-column">
                               <li class="nav-item">
                                    <a class="nav-link" href="auth-login.html">Log in</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-login-alt.html">Log in alt</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-register.html">Register</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-register-alt.html">Register-alt</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-recover-pw.html">Re-Password</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-recover-pw-alt.html">Re-Password-alt</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-lock-screen.html">Lock Screen</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-lock-screen-alt.html">Lock Screen-alt</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-404.html">Error 404</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-404-alt.html">Error 404-alt</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-500.html">Error 500</a>
                                </li><!--end nav-item-->
                                <li class="nav-item">
                                    <a class="nav-link" href="auth-500-alt.html">Error 500-alt</a>
                                </li><!--end nav-item-->
                            </ul><!--end nav-->
                        </div><!--end sidebarAuthentication-->
                    </li><!--end nav-item--> --}}

                    
                </ul><!--end navbar-nav--->
            </div><!--end sidebarCollapse-->
        </div>
    </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 1228px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none; width: 126px;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 498px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></div>    
</div>
<!-- end left-sidebar-->