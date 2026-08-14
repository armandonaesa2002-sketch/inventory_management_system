<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Add Multiple Asset</title>
</head>
<body class="inventory-body">
<div class="container">
    <section class="card">
        <header class="form-header">
            <div>
                <h1 class="form-title">{{$asset->asset_tag}}</h1>
                <p class="form-subtitle">
                    <span class="badge badge-type caps">{{$asset->device_type}}</span>
                    <span class="badge badge-status caps">{{$asset->status}}</span>
                    @if ($asset->warranty_expiry)
                        @php
                            $today = now()->startOfDay();
                            $days_left = $today->diffInDays($asset->warranty_expiry, false);
                        @endphp
                        @if ($days_left < 0)
                            <span class="badge badge-warranty-expired">Warranty Expired</span>
                        @elseif ($days_left <= 30)
                            <span class="badge badge-warranty-soon">Warranty Expiring Soon</span>
                        @else
                            <span class="badge badge-warranty-active">Warranty Active</span>
                        @endif
                    @else
                        <span class="badge badge-muted">No Warranty</span>
                    @endif
                </p>
            </div>
            <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                Back to List
            </a>
        </header>

        <div class="bulk-grid">
            <!-- LEFT: ASSET INFORMATION -->
            <div class="bulk-column">
                <h2 class="step-title">Asset Information</h2>

                <div class="detail-group">
                    <div class="detail-row">
                        <span class="detail-label">Brand</span>
                        <span class="detail-value caps">{{$asset->brand}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Model</span>
                        <span class="detail-value caps">{{$asset->model}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Serial Number</span>
                        <span class="detail-value caps">{{$asset->serial_number}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Purchase Date</span>
                        <span class="detail-value caps">{{$asset->purchase_date ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Warranty Expiry</span>
                        <span class="detail-value caps">{{$asset->warranty_expiry ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Vendor</span>
                        <span class="detail-value caps">{{$asset->vendor ?? '—'}}</span>
                    </div>
                </div>

                @if($asset->device_type !== 'printer')
                <h3 class="detail-subtitle">Hardware</h3>
                <div class="detail-group">
                    <div class="detail-row">
                        <span class="detail-label">Processor</span>
                        <span class="detail-value caps">{{$asset->hardware?->processor ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">RAM (GB)</span>
                        <span class="detail-value caps">{{$asset->hardware?->ram_gb.' GB' ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Storage</span>
                        <span class="detail-value caps">{{$asset->hardware?->storage ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">GPU</span>
                        <span class="detail-value caps">{{$asset->hardware?->gpu ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Power Supply</span>
                        <span class="detail-value caps">{{$asset->hardware?->power_supply ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Monitor</span>
                        <span class="detail-value caps">{{$asset->hardware?->monitor ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Peripherals</span>
                        <span class="detail-value caps">{{$asset->hardware?->peripherals ?? '—'}}</span>
                    </div>
                </div>

                <h3 class="detail-subtitle">Software</h3>
                <div class="detail-group">
                    <div class="detail-row">
                        <span class="detail-label">Operating System</span>
                        <span class="detail-value caps">{{$asset->software?->operating_system ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Product Key OS</span>
                        <span class="detail-value caps">{{$asset->software?->product_key_os ?? '—'}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Product Key Others</span>
                        <span class="detail-value caps">{{$asset->software?->product_key_other ?? '—'}}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- RIGHT: assignment + repair tables (yung existing mo, ok na) -->
            <div class="bulk-column">
                <h2 class="step-title">Assignment History</h2>
                <div class="history-card">
                    <div class="table-wrapper history-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Department</th>
                                    <th>Location</th>
                                    <th>Date Assigned</th>
                                    <th>Date Updated</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asset->assignment as $assignment)
                                <tr>
                                    <td>{{$assignment->user_name}}</td>
                                    <td>{{$assignment->department}}</td>
                                    <td>{{$assignment->location}}</td>
                                    <td>{{$assignment->created_at}}</td>
                                    <td>{{$assignment->updated_at}}</td>
                                    <td>{{$assignment->status}}</td>
                                    
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">No record yet</td>
                                </tr>
                                @endforelse
                                
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <h2 class="step-title" style="margin-top: 18px;">Repair History</h2>
                <div class="history-card">
                    <div class="table-wrapper history-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Date Created</th>
                                    <th>Date Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asset->repair_history as $repair_history)
                                <tr>
                                    <td>{{$repair_history->user_name}}</td>
                                    <td>{{$repair_history->repair_status}}</td>
                                    <td>{{$repair_history->description}}</td>
                                    <td>{{$repair_history->created_at}}</td>
                                    <td>{{$repair_history->updated_at}}</td>
                                    
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">No record yet</td>
                                </tr>
                                @endforelse
                                
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>


</body>

</html>