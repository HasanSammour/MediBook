<div class="doctors-grid">
    @forelse($doctors as $doctor)
        @php
            $avatarUrl = $doctor->avatar_url;
            $isSvg = str_starts_with($avatarUrl, 'data:image/svg+xml');
            $appointmentCount = $doctor->doctor_appointments_count ?? 0;
            $experience = $doctor->calculated_experience ?? rand(3, 15);
        @endphp
        <div class="doctor-card">
            <div class="doctor-image">
                @if($isSvg)
                    <div class="doctor-avatar-fallback">
                        {!! $avatarUrl !!}
                    </div>
                @else
                    <img src="{{ $avatarUrl }}" alt="{{ $doctor->name }}"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="doctor-avatar-fallback" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                            <rect width="100" height="100" fill="#2563eb" rx="50" />
                            <text x="50" y="50" text-anchor="middle" dy=".3em" fill="white" font-size="40"
                                font-weight="600">{{ substr($doctor->name, 0, 2) }}</text>
                        </svg>
                    </div>
                @endif
                <div class="doctor-badge"><i class="fas fa-check-circle"></i> Verified</div>
            </div>
            <div class="doctor-info">
                <div class="doctor-name">
                    <h3>{{ $doctor->name }}</h3>
                </div>
                <span class="doctor-specialty">{{ $doctor->specialization ?? 'General Physician' }}</span>
                <div class="doctor-hospital">
                    <i class="fas fa-hospital"></i>
                    <span>{{ $doctor->hospital->name ?? 'Independent Practice' }}</span>
                </div>
                <div class="doctor-details">
                    <div class="detail-item">
                        <div class="detail-value">{{ $experience }}+</div>
                        <div class="detail-label">Years Exp</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-value">{{ $appointmentCount }}+</div>
                        <div class="detail-label">Patients</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-value">English</div>
                        <div class="detail-label">Language</div>
                    </div>
                </div>
                <div class="doctor-fee">Consultation Fee: ${{ number_format($doctor->consultation_fee ?? 100, 2) }}</div>
                <div class="doctor-availability {{ $doctor->is_available ? 'available' : 'unavailable' }}">
                    {{ $doctor->is_available ? 'Available' : 'Unavailable' }}
                </div>
                <div class="doctor-actions mt-3">
                    <button class="btn btn-outline btn-sm" onclick="viewDoctorProfile({{ $doctor->id }})">View Profile</button>
                    @if($doctor->is_available)
                        <button class="btn btn-primary btn-sm" onclick="bookDoctorAppointment({{ $doctor->id }})">Book Appointment</button>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled style="background: #9ca3af; cursor: not-allowed;">Not Available</button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-user-md"></i>
            <h3>No doctors found</h3>
            <p>Try adjusting your search criteria</p>
            <a href="{{ route('doctors') }}" class="btn btn-outline btn-sm mt-3">Reset Filters</a>
        </div>
    @endforelse
</div>