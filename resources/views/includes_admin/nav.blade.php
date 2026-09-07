<div class="sidebar no-print" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul>


				@if(!in_array(auth()->user()->app_type, [5, 4, 25, 26]))
				<li><a href="{{ route('admin.dashboard') }}"><span class="menu-side"><img
								src="/assets/img/icons/menu-icon-01.svg"
								alt=""></span>
						<span>@lang('admin.dashboard')</span></a></li>
				@endif
				<li><a href="{{ route('profile') }}"><span
							class="menu-side clinic-admin-menu-icon"><i
								class="fa-solid fa-user-gear"></i></span>
						<span>@lang('admin.doctor.personal_information')</span></a>
				</li>

				@if(auth()->user()->app_type == 6)

				<li><a href="{{ route('notifications') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/notifications.png"
								alt=""></span>
						<span>@lang('admin.doctor.Notifications')</span></a></li>


				@if(auth()->user()->app_type == 6)

				<li><a href="{{ route('cities') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/menu-icon-02.svg"
								alt=""></span>
						<span>@lang('admin.all_cities')</span></a></li>

				<li><a href="{{ route('clinics') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/personal_information.png"
								alt=""></span>
						<span>@lang('main.clinics')</span></a></li>

				<li><a href="{{ route('users') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/menu-icon-02.svg"
								alt=""></span>
						<span>@lang('main.users')</span></a></li>

				<li><a href="{{ route('complains-box') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/menu-icon-10.svg"
								alt=""></span>
						<span>@lang('main.Complaints Box')</span></a></li>

				<li><a href="{{ route('main-specialties') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/menu-icon-06.svg"
								alt=""></span>
						<span>@lang('admin.specialties')</span></a></li>

				<li><a href="{{ route('cities') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/menu-icon-06.svg"
								alt=""></span>
						<span>@lang('admin.all_cities')</span></a></li>

				<li><a href="{{ route('reports.index') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/personal_information.png"
								alt=""></span>
						<span>@lang('main.reports')</span></a></li>

				<li><a href="{{ route('financial-reports.index') }}"><span
							class="menu-side clinic-admin-menu-icon"><i
								class="fa-solid fa-file-invoice-dollar"></i></span>
						<span>@lang('financial_reports.title')</span></a></li>

				<li><a href="{{ route('analytics.index') }}"><span
							class="menu-side clinic-admin-menu-icon"><i
								class="fa-solid fa-chart-line"></i></span>
						<span>@lang('analytics.title')</span></a></li>

				<li><a href="{{ route('points') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/personal_information.png"
								alt=""></span>
						<span>@lang('main.all_points')</span></a></li>

				<li><a href="{{ route('loyalty-organizations') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/my_points.png"
								alt=""></span>
						<span>@lang('main.loyalty_organizations')</span></a></li>

				<li><a href="{{ route('loyalty-point-rules') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/my_points.png"
								alt=""></span>
						<span>@lang('main.loyalty_point_rules')</span></a></li>

				<li><a href="{{ route('admin-supervisor') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/personal_information.png"
								alt=""></span>
						<span>@lang('admin.SuperVisor Management')</span></a></li>

				<li><a href="{{ route('packages.index') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/personal_information.png"
								alt=""></span>
						<span>@lang('main.packages')</span></a></li>

				<li><a href="{{ route('permissionsTypes.index') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/menu-icon-06.svg"
								alt=""></span>
						<span>@lang('main.permissions_types')</span></a></li>

				<li>
					<a class="active" href="{{ route('app-setting',['terms',1]) }}"><span
							class="menu-side"><img
								src="/media/icons/menu-icon-16.svg"
								alt=""></span>
						<span>@lang('admin.setting')</span></a>
				</li>
				@endif

				<li><a href="{{ route('doctor-attendance-departure') }}"><span
							class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/Attendance and Departure.png"
								alt=""></span>
						<span>@lang('admin.doctor.Attendance and
							Departure')</span></a></li>

				<li><a href="{{ route('employee-clinic-shifts') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/personal_information.png"
								alt=""></span>
						<span>@lang('admin.doctor.Shifts')</span></a></li>


				<li><a href="{{ route('request-permission') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/Attendance and Departure.png"
								alt=""></span>
						<span>@lang('admin.doctor.request a permission')</span></a>
				</li>


				<li><a href="{{ route('points') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/my_points.png"
								alt=""></span>
						<span>@lang('admin.My Points')</span></a></li>
				<li><a href="{{ route('change-password') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/change_password.png"
								alt=""></span>
						<span>@lang('admin.Change Password')</span></a></li>
				@if(auth()->user()->app_type != 6)
				<li><a href="{{ route('setting','about') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/abut_app.png"
								alt=""></span>
						<span>@lang('admin.About The App')</span></a></li>
				<li><a href="{{ route('setting','privacy') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/Privacy Policy.png"
								alt=""></span>
						<span>@lang('admin.Privacy Policy')</span></a></li>
				<li><a href="{{ route('setting','terms') }}"><span class="menu-side"><img
								style="background-color: #888"
								src="/media/icons/Terms of use.png"
								alt=""></span>
						<span>@lang('admin.Terms of use')</span></a></li>
				@endif

			</ul>
			<div class="logout-btn">
				<a href="{{ route('admin.logout') }}"><span class="menu-side"><img
							src="/assets/img/icons/logout.svg" alt=""></span>
					<span>@lang('admin.Sign out')</span></a>
			</div>


			<!-- reception -->
			@elseif(auth()->user()->app_type == 2)
			<li><a href="{{ route('appointments') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-calendar-check"></i></span>
					<span>@lang('admin.reception.appointments_list')</span></a></li>
			<li><a href="{{route('add-patient')}}"><span class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-user-plus"></i></span>
					<span>@lang('admin.reception.add_patient')</span></a></li>
			<li><a href="{{ route('add-appointment') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-calendar-plus"></i></span>
					<span>@lang('admin.reception.add_appointment')</span></a></li>
			<li><a href="{{ route('chatList') }}"><span class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-comments"></i></span>
					<span>@lang('admin.chat_list')</span></a></li>


			<!-- pharmacy -->
			@elseif(in_array(auth()->user()->app_type, [5, 4, 25, 26]))
			@clinicModule('points')
			<li><a href="{{ route('loyalty.redemptions') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-gift"></i></span>
					<span>@lang('main.loyalty_program')</span></a></li>
			@endclinicModule
			<!-- <li><a href="{{ route('points') }}"><span class="menu-side"><img
							style="background-color: #888"
							src="/media/icons/my_points.png" alt=""></span>
					<span>@lang('admin.My Points')</span></a></li> -->
			<li><a href="{{ route('change-password') }}"><span class="menu-side"><img
							style="background-color: #888"
							src="/media/icons/change_password.png"
							alt=""></span>
					<span>@lang('admin.Change Password')</span></a></li>
			<li><a href="{{ route('setting','about') }}"><span class="menu-side"><img
							style="background-color: #888"
							src="/media/icons/abut_app.png" alt=""></span>
					<span>@lang('admin.About The App')</span></a></li>
			<li><a href="{{ route('setting','privacy') }}"><span class="menu-side"><img
							style="background-color: #888"
							src="/media/icons/Privacy Policy.png"
							alt=""></span>
					<span>@lang('admin.Privacy Policy')</span></a></li>
			<li><a href="{{ route('setting','terms') }}"><span class="menu-side"><img
							style="background-color: #888"
							src="/media/icons/Terms of use.png" alt=""></span>
					<span>@lang('admin.Terms of use')</span></a></li>


			@else

			<!-- clinic -->
			@if(in_array(auth()->user()->app_type, [1, 7,11 , 5]))
			<li><a href="{{ route('admin.doctors.ratings') }}"
					class="{{ request()->routeIs('admin.doctors.ratings') ? 'active' : '' }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-star-half-stroke"></i></span>
					<span>@lang('admin.doctors_ratings')</span></a></li>

			@clinicModule('points')
			<li><a href="{{ route('loyalty.redemptions') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-gift"></i></span>
					<span>@lang('main.loyalty_program')</span></a></li>
			@endclinicModule

			<li><a href="{{ route('departments') }}"><span class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-layer-group"></i></span>
					<span>@lang('admin.departments')</span></a></li>

			<li><a href="{{ route('offers') }}"><span class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-tags"></i></span>
					<span>@lang('admin.Manage Offers')</span></a></li>

			<li><a href="{{ route('specialties') }}"><span class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-stethoscope"></i></span>
					<span>@lang('admin.Manage available specialties')</span></a></li>

			@if(auth()->user()->app_type != 7)
			<li><a href="{{ route('branches') }}"><span class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-code-branch"></i></span>
					<span>@lang('admin.Manage Branch')</span></a></li>
			@endif

			<li><a href="{{ route('clinic.supervisor') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-user-shield"></i></span>
					<span>@lang('admin.SuperVisor Management')</span></a></li>

			<li><a href="{{ route('contactUs') }}"><span class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-comments"></i></span>
					<span>@lang('admin.Complaints Box')</span></a></li>

			<li><a href="{{ route('financial-reports.index') }}"
					class="{{ request()->routeIs('financial-reports.index') ? 'active' : '' }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-file-invoice-dollar"></i></span>
					<span>@lang('financial_reports.title')</span></a></li>

			<li><a href="{{ route('clinic-reports.appointments') }}"
					class="{{ request()->routeIs('clinic-reports.appointments') ? 'active' : '' }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-calendar-check"></i></span>
					<span>@lang('clinic_reports.appointments')</span></a></li>

			<li><a href="{{ route('clinic-reports.doctors') }}"
					class="{{ request()->routeIs('clinic-reports.doctors') ? 'active' : '' }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-user-doctor"></i></span>
					<span>@lang('clinic_reports.doctors')</span></a></li>

			<li><a href="{{ route('clinic-reports.patients') }}"
					class="{{ request()->routeIs('clinic-reports.patients') ? 'active' : '' }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-users"></i></span>
					<span>@lang('clinic_reports.patients')</span></a></li>

			<li><a href="{{ route('clinic-reports.reviews') }}"
					class="{{ request()->routeIs('clinic-reports.reviews') ? 'active' : '' }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-star"></i></span>
					<span>@lang('clinic_reports.reviews')</span></a></li>

			<li><a href="{{ route('change-password') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-key"></i></span>
					<span>@lang('admin.Change Password')</span></a></li>

			<li><a href="{{ route('setting','about') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-circle-info"></i></span>
					<span>@lang('admin.About The App')</span></a></li>
			<li><a href="{{ route('setting','privacy') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-shield-halved"></i></span>
					<span>@lang('admin.Privacy Policy')</span></a></li>
			<li><a href="{{ route('setting','terms') }}"><span
						class="menu-side clinic-admin-menu-icon"><i
							class="fa-solid fa-file-contract"></i></span>
					<span>@lang('admin.Terms of use')</span></a></li>

			@else
			{{--                <li><a href="{{ route('doctor-medical-reports') }}"><span
				class="menu-side">--}}
				{{--                            <img style="background-color: #888" src="/media/icons/View patient appointments.png" alt=""></span>--}}
				{{--                        <span>@lang('admin.doctor.View medical reports')</span></a></li>--}}

				<li><a href="{{ route('patients-waiting') }}"><span class="menu-side">
							<img style="background-color: #888"
								src="/media/icons/View patient appointments.png"
								alt=""></span>
						<span>@lang('admin.doctor.waiting_list')</span></a></li>

				{{--                <li><a href="{{ route('prescription-record') }}"><span
					class="menu-side">--}}
					{{--                            <img style="background-color: #888" src="/media/icons/prescription record.png"--}}
					{{--                                 alt=""></span>--}}
					{{--                        <span>@lang('admin.doctor.prescription record')</span></a></li>--}}

					<li><a href="{{ route('doctor-appointment') }}"><span
								class="menu-side">
								<img style="background-color: #888"
									src="/media/icons/doctor_appointments.png"
									alt=""></span>
							<span>@lang('admin.doctor.doctor_appointments')</span></a>
					</li>

					<li><a href="{{ route('patient-appointment') }}"><span
								class="menu-side">
								<img style="background-color: #888"
									src="/media/icons/View patient appointments.png"
									alt=""></span>
							<span>@lang('admin.doctor.View patient
								appointments')</span></a></li>

					<li>
						<a href="{{route('chat')}}"><span class="menu-side"><img
									src="/media/icons/menu-icon-10.svg"
									alt=""></span>
							<span>@lang('admin.chat_list')</span></a>
					</li>

					<li class="submenu">
						<a href="#"><span class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/Medical_prescription_menu.png"
									alt=""></span>
							<span> @lang('admin.doctor.Drug lists') </span>
							<span class="menu-arrow"></span></a>
						<ul style="display: none;">
							<li><a href="{{ route('drugs') }}">@lang('admin.doctor.Drug
									lists')</a></li>
							<li><a href="{{ route('create-drug') }}">@lang('admin.doctor.add_drug')</a>
							</li>
						</ul>
					</li>

					<li><a href="{{ route('doctor-attendance-departure') }}"><span
								class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/Attendance and Departure.png"
									alt=""></span>
							<span>@lang('admin.doctor.Attendance and
								Departure')</span></a></li>

					<li><a href="{{ route('employee-clinic-shifts') }}"><span
								class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/personal_information.png"
									alt=""></span>
							<span>@lang('admin.doctor.Shifts')</span></a></li>


					<li><a href="{{ route('request-permission') }}"><span
								class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/Attendance and Departure.png"
									alt=""></span>
							<span>@lang('admin.doctor.request a
								permission')</span></a></li>
					<li><a href="{{ route('notifications') }}"><span
								class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/notifications.png"
									alt=""></span>
							<span>@lang('admin.doctor.Notifications')</span></a>
					</li>


					<li><a href="{{ route('contactUs') }}"><span class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/questions received menu.png"
									alt=""></span>
							<span>@lang('admin.doctor.questions
								received')</span></a></li>

					<li><a href="{{ route('points') }}"><span class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/my_points.png"
									alt=""></span>
							<span>@lang('admin.My Points')</span></a></li>
					<li><a href="{{ route('change-password') }}"><span
								class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/change_password.png"
									alt=""></span>
							<span>@lang('admin.Change Password')</span></a>
					</li>
					<li><a href="{{ route('setting','about') }}"><span
								class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/abut_app.png"
									alt=""></span>
							<span>@lang('admin.About The App')</span></a></li>
					<li><a href="{{ route('setting','privacy') }}"><span
								class="menu-side"><img
									style="background-color: #888"
									src="/media/icons/Privacy Policy.png"
									alt=""></span>
							<span>@lang('admin.Privacy Policy')</span></a>
					</li>

					<li><a href="{{ route('setting','terms') }}"><span class="menu-side">
								<img style="background-color: #888"
									src="/media/icons/Terms of use.png"
									alt=""></span>
							<span>@lang('admin.Terms of use')</span></a></li>
					@endif
					</ul>
					<div class="logout-btn">
						<a href="{{ route('admin.logout') }}"><span
								class="menu-side"><img
									src="/assets/img/icons/logout.svg"
									alt=""></span>
							<span>@lang('admin.Sign out')</span></a>
					</div>
					@endif
		</div>
	</div>
</div>