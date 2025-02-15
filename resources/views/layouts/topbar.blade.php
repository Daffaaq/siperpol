<?php
$alerts = \App\Models\Alert::select('message', 'sended_at', 'type', 'is_read', 'id', 'title', 'users_id')
    ->where('users_id', auth()->user()->id)
    ->get();
$messages = \App\Models\Message::select('message', 'status', 'sended_time', 'id', 'sender', 'users_id')
    ->where('users_id', auth()->user()->id)
    ->get();

$alertsAll = \App\Models\Alert::select('message', 'sended_at', 'type', 'is_read', 'id', 'title', 'users_id')
    ->where('users_id', auth()->user()->id)
    ->get(); // Pagination 5 alerts per page

$messagesAll = \App\Models\Message::select('message', 'status', 'sended_time', 'id', 'sender', 'users_id')
    ->where('users_id', auth()->user()->id)
    ->get(); // Pagination 5 messages per page

?>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">
                    {{ count($alerts->where('is_read', false)) > 0 ? count($alerts->where('is_read', false)) : '0' }}
                </span>

                {{-- <span
                    class="badge badge-danger badge-counter">{{ count($alerts->where('is_read', false)) > 0 ? count($alerts) : '0' }}</span> --}}
            </a>
            <!-- Dropdown - Alerts -->

            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <div class="alert-list" style="max-height: 200px; overflow-y: auto;">
                    @foreach ($alerts as $alert)
                        <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                            data-target="#alertModal" data-id="{{ $alert->id }}" data-title="{{ $alert->title }}"
                            data-message="{{ $alert->message }}"
                            data-sended_at="{{ \Carbon\Carbon::parse($alert->sended_at)->format('F j, Y g:i A') }}"
                            data-type="{{ $alert->type }}">

                            <div class="mr-3">
                                @if ($alert->type === 'info')
                                    <!-- Blue info icon for unread alert -->
                                    <div class="icon-circle bg-info">
                                        <i class="fas fa-info-circle text-white"></i>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="small text-gray-500">
                                    {{ \Carbon\Carbon::parse($alert->sended_at)->format('F j, Y') }}
                                </div>
                                @if ($alert->is_read)
                                    <span class="font-weight-bold" title="{{ $alert->message }}">{{ $alert->title }} <i
                                            class="fas fa-check text-success iconisread"></i></span>
                                @else
                                    <span class="font-weight-bold" title="{{ $alert->message }}">{{ $alert->title }} <i
                                            class="fas fa-exclamation-triangle text-warning iconisread"></i></span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                <a class="dropdown-item text-center" href="#" data-toggle="modal" data-target="#allAlertsModal">
                    See All Alerts
                </a>

            </div>
            {{-- <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 12, 2019</div>
                        <span class="font-weight-bold">A new monthly report is ready to
                            download!</span>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 7, 2019</div>
                        $290.29 has been deposited into your account!
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 2, 2019</div>
                        Spending Alert: We've noticed unusually high spending for your account.
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-danger">
                            <i class="fas fa-heart-broken text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 2, 2019</div>
                        Spending Alert: We've noticed unusually high spending for your account.
                    </div>
                </a>
            </div> --}}
        </li>

        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->

                {{-- <span
                    class="badge badge-danger badge-counter">{{ count($messages->where('status', 'unread')) > 0 ? count($messages) : '0' }}</span> --}}
                <span
                    class="badge badge-danger badge-counter">{{ count($messages->where('status', 'unread')) > 0 ? count($messages->where('status', 'unread')) : '0' }}</span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                <div class="message-list" style="max-height: 200px; overflow-y: auto;">
                    @foreach ($messages as $message)
                        <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                            data-target="#messageModal" data-id="{{ $message->id }}"
                            data-message="{{ $message->message }}" data-sender="{{ $message->sender }}"
                            data-status="{{ $message->status }}"
                            data-sended_time="{{ \Carbon\Carbon::parse($message->sended_time)->format('F j, Y g:i A') }}">
                            <!-- Custom Status Indicator -->
                            @if ($message->status == 'unread')
                                <div class="indicatornotif bg-danger"
                                    style="width: 15px; height: 15px; border-radius: 50%; margin-right: 10px;"></div>
                            @elseif($message->status == 'read')
                                <div class="indicatornotif bg-success"
                                    style="width: 15px; height: 15px; border-radius: 50%; margin-right: 10px;"></div>
                            @endif
                            <div>
                                <div class="text-truncate" title="{{ $message->message }}">{{ $message->message }}
                                </div>
                                <div class="small text-gray-500">{{ $message->sender }} .
                                    {{ \Carbon\Carbon::parse($message->sended_time)->diffForHumans() }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <a class="dropdown-item text-center" href="#" data-toggle="modal"
                    data-target="#allMessagesModal">
                    See All Messages
                </a>
                {{-- <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="indicatornotif bg-danger"
                        style="width: 15px; height: 15px; border-radius: 50%; margin-right: 10px;">
                    </div>
                    <div>
                        <div class="text-truncate">I have the photos that you ordered last month, how
                            would you like them sent to you?</div>
                        <div class="small text-gray-500">Jae Chun · 1d</div>
                    </div>
                </a> --}}
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('sb-admin/img/undraw_profile.svg') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ url('/logout') }}" data-toggle="modal"
                    data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Here we will inject the message content dynamically -->
                <div id="messageSender"></div>
                <div id="messageContent"></div>
                <div id="messageStatus"></div>
                <div id="messageTime"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Alert Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Here we will inject the alert content dynamically -->
                <div id="alertTitle"></div>
                <div id="alertMessage"></div>
                <div id="alertTime"></div>
                <div id="alertType"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- All Alerts Modal -->
<div class="modal fade" id="allAlertsModal" tabindex="-1" role="dialog" aria-labelledby="allAlertsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allAlertsModalLabel">All Alerts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto; padding-right: 15px;">
                @foreach ($alertsAll as $alert)
                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                        data-target="#alertModal" data-id="{{ $alert->id }}" data-title="{{ $alert->title }}"
                        data-message="{{ $alert->message }}"
                        data-sended_at="{{ \Carbon\Carbon::parse($alert->sended_at)->format('F j, Y g:i A') }}"
                        data-type="{{ $alert->type }}">
                        <div class="mr-3">
                            @if ($alert->type === 'info')
                                <div class="icon-circle bg-info">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="small text-gray-500">
                                {{ \Carbon\Carbon::parse($alert->sended_at)->format('F j, Y') }}
                            </div>
                            <span class="font-weight-bold" title="{{ $alert->message }}">{{ $alert->title }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- All Messages Modal -->
<div class="modal fade" id="allMessagesModal" tabindex="-1" role="dialog" aria-labelledby="allMessagesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allMessagesModalLabel">All Messages</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto; padding-right: 15px;">
                @foreach ($messagesAll as $message)
                    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                        data-target="#messageModal" data-id="{{ $message->id }}"
                        data-message="{{ $message->message }}" data-sender="{{ $message->sender }}"
                        data-status="{{ $message->status }}"
                        data-sended_time="{{ \Carbon\Carbon::parse($message->sended_time)->format('F j, Y g:i A') }}">
                        <div>
                            <div class="text-truncate" title="{{ $message->message }}">{{ $message->message }}</div>
                            <div class="small text-gray-500">{{ $message->sender }} .
                                {{ \Carbon\Carbon::parse($message->sended_time)->diffForHumans() }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




@push('styles')
    <style>
        .status-indicator {
            width: 15px;
            /* Adjust size */
            height: 15px;
            /* Adjust size */
            border-radius: 50%;
            /* Circular shape */
            background-color: #28a745;
            /* Success green color */
            margin-right: 10px;
            /* Spacing between indicator and text */
            display: inline-block;
            /* Ensures it's aligned properly */
        }
    </style>
@endpush
@push('scripts')
    <script>
        // On modal open, fill the message content dynamically
        $('#messageModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var messageId = button.data('id');
            var messageContent = button.data('message');
            var sender = button.data('sender');
            var status = button.data('status');
            var sendedTime = button.data('sended_time');
            var messagesUnreadCount = $('#messagesDropdown .badge-counter'); // Get the current unread message count

            var modal = $(this);

            modal.find('#messageSender').text('Sender: ' + sender);
            modal.find('#messageContent').text('Message: ' + messageContent);
            modal.find('#messageStatus').text('Status: ' + (status === 'unread' ? 'Unread' : 'Read'));
            modal.find('#messageTime').text('Sent: ' + sendedTime);

            if (status === 'unread') {
                $.ajax({
                    url: '/message/read/' + messageId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            modal.find('#messageStatus').text('Status: Read');
                            button.find('.indicatornotif').removeClass('bg-danger').addClass(
                                'bg-success');

                            // Decrement the unread count after the message is marked as read
                            var currentCount = parseInt(messagesUnreadCount.text());
                            if (currentCount > 0) {
                                messagesUnreadCount.text(currentCount - 1); // Decrease the unread count
                            }
                        }
                    },
                    error: function(error) {
                        console.error('Error updating message status:', error);
                    }
                });
            }
        });

        // On modal open, fill the alert content dynamically
        $('#alertModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var alertId = button.data('id');
            var alertTitle = button.data('title');
            var alertMessage = button.data('message');
            var alertTime = button.data('sended_at');
            var alertType = button.data('type');
            var alertsUnreadCount = $('#alertsDropdown .badge-counter'); // Get the current unread alert count

            var modal = $(this);

            modal.find('#alertTitle').text('Title: ' + alertTitle);
            modal.find('#alertMessage').text('Message: ' + alertMessage);
            modal.find('#alertTime').text('Sent: ' + alertTime);
            modal.find('#alertType').text('Type: ' + alertType);

            $.ajax({
                url: '/alert/read/' + alertId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.status === 'success') {
                        button.find('.iconisread').removeClass('fa-exclamation-triangle text-warning')
                            .addClass('fa-check text-success');

                        // Decrement the unread count after the alert is marked as read
                        var currentCount = parseInt(alertsUnreadCount.text());
                        if (currentCount > 0) {
                            alertsUnreadCount.text(currentCount - 1); // Decrease the unread count
                        }
                    }
                },
                error: function(error) {
                    console.error('Error updating alert status:', error);
                }
            });
        });
    </script>
@endpush
