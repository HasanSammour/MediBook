<div class="hospitals-grid">
    @forelse($hospitals as $hospital)
        @php
            $logoUrl = $hospital->logo_url;
            $isSvg = str_starts_with($logoUrl, 'data:image/svg+xml');
            $doctorsCount = $hospital->doctors_count ?? $hospital->doctors()->count();
        @endphp
        <div class="hospital-card">
            <div class="hospital-image">
                @if($isSvg)
                    <div class="hospital-logo-fallback">
                        {!! $logoUrl !!}
                    </div>
                @else
                    <img src="{{ $logoUrl }}" alt="{{ $hospital->name }}"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hospital-logo-fallback" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 100 100">
                            <rect width="100" height="100" fill="#2563eb" rx="16" />
                            <text x="50" y="65" text-anchor="middle" fill="white" font-size="45">🏥</text>
                        </svg>
                    </div>
                @endif
                <div class="hospital-badge"><i class="fas fa-check-circle"></i> Verified</div>
            </div>
            <div class="hospital-info">
                <div class="hospital-name">
                    <h3>{{ $hospital->name }}</h3>
                </div>
                <div class="hospital-address">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ Str::limit($hospital->address, 80) }}</span>
                </div>
                <div class="hospital-contact">
                    <span><i class="fas fa-phone"></i> {{ $hospital->phone }}</span>
                    <span><i class="fas fa-envelope"></i> {{ $hospital->email }}</span>
                </div>
                <div class="hospital-stats">
                    <div class="stat-item">
                        <div class="stat-value"><i class="fas fa-user-md"></i> {{ $doctorsCount }}</div>
                        <div class="stat-label">Doctors</div>
                    </div>
                </div>
                <div class="hospital-actions">
                    <button class="btn btn-outline btn-sm" onclick="viewHospitalDetails({{ $hospital->id }})">View Details</button>
                    <button class="btn btn-primary btn-sm" onclick="bookAtHospital({{ $hospital->id }})">Book Appointment</button>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-hospital"></i>
            <h3>No hospitals found</h3>
            <p>Try adjusting your search criteria</p>
            <a href="{{ route('hospitals') }}" class="btn btn-outline btn-sm mt-3">Reset Filters</a>
        </div>
    @endforelse
</div>
