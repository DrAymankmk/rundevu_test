<?php
return [

    'auth' => [
        'account_exists' => 'Email Already Exists',
        'register' => 'Welcome in Takafoul App',
        'city_list' => 'show data successfully',

        'login' => 'Login Successfully',
        'password' => 'Incorrect Password',
        'key' => 'Email Incorrect',
        'check_email' => 'Incorrect Email',
        'email' => 'incorrect email',
        'phone' => 'incorrect phone number',
        'phone_correct' => 'correct phone number',
        'communication_officer' => 'Responsible Person Number Required',
        'phone_password' => 'data incorrect',
        'phone_exist' => 'Phone Number exist',
        'phone_not_exist' => 'Phone Number Not exist',
        'account_suspended' => 'Your Account is Suspended by The Administrator',
        'wrong_activate_code' => 'Wrong Activation Code, Please Check and Try Again',
        'activated_successfully' => 'Account Activated Successfully',
        'active_account' => 'Please Active Account',
        'phone_not_exists' => 'No Account Found with this Phone Number, Please Try Again',
        'message_sent' => 'Check Your Message For Activation Code ',
        'message' => 'You Must Enter Your Message',
        'send message' => 'Send Message Successfully',
        'logout' => 'Logout Successfully',

        'forgetPassword' => 'Password Updated Successfully',
        'user_check' => 'Please Login first',


        'name' => 'Name is Required',
        'info' => 'Info is Required',
        'date_created' => 'Date Created is Required',
        'city_id' => 'City is Required',
        'address' => 'address is Required',
        'lat' => 'lat is Required',
        'lng' => 'lng is Required',
        'image' => 'image is Required',
        'qr_code' => 'qr_code is Required',
        'newPassword' => 'newPassword is Required',


        'edit_profile' => 'Profile Updated Successfully',
        'phone_another_account' => 'Phone Already Exist Another Account',
        'email_another_account' => 'Email Exist Another Account',
        
         'reset_password_monthly_limit' => 'You can request a password reset code once per month',


    ],

    'something_went_wrong' => 'Something Went Wrong, Please Try Again Later',
    'password' => 'Password Is Required',
    'email' => 'Email Is Required',
    'phone' => 'Phone number Is Required',
    'validate_lang' => 'incorrect Language',

    'cities' => [
        'all' => 'show cities'
    ],

    'profile' => [
        'user_details' => 'User Details',
        'added' => 'User Details Added Successfully',
        'updated' => 'User Details Updated Successfully',
        'password_updated' => 'Password Updated Successfully',
        'wrong_old_password' => 'Wrong Old Password',
        'lang_updated' => 'The Language Was Updated Successfully',
        'old_password_same_as_new' => 'Your Old Password is The Same as New Password',
        'info' => 'User Information',
        'logged_out' => 'User Logged Out',
    ],



    'posts' => [
        'all' => 'Show Clinic Posts',
        'content' => 'Content Post Required',
        'image' => 'Image Post Required',
        'id' => 'post Id Required',
        'delete' => 'Delete Post Successfully',
    ],
    'Added' => 'Data Added Successfully',

    'department' => [
        'all' => 'Show All Departments',
        'name_ar' => 'Name With Arabic Required',
        'name_en' => 'Name With English Required',
        'id' => 'Please Send Department ID',
        'change_status' => 'Change Status Department Successfully',
    ],
    'staff' => [
        'all' => 'Show All Departments',
        'date_from' => 'Date From Required',
        'date_to' => 'Date To Required',
        'id' => 'Please Send Department ID',
        'change_status' => 'Change Status Department Successfully',
    ],

    'shift' => [
        'all' => 'Show All Shift',
        'time_from' => 'Time From Required',
        'time_to' => 'Time To Required',
        'id' => 'Please Send Shift ID',
        'status'=>'Shift type required (shift or holiday)',

        'minute_allow_delay' => 'Minute Allow For Delay',
    ],

    'specialties' => [
        'all' => 'Show All specialties',
        'id' => 'Please Send specialty ID',
        'delete' => 'Delete specialty Successfully',
    ],

    'offers' => [
        'all' => 'Show Clinic offer',
        'discount' => 'Discount Percentage',
        'title_ar' => 'Title with Arabic Required',
        'title_en' => 'Title with English Required',
        'id' => 'offer Id Required',
        'delete' => 'Delete offer Successfully',
    ],

    'branches' => [
        'all' => 'Show Clinic Branches',
        'id' => 'Branch Id Required',
        'change_status' => 'Change Status Branch Successfully',
    ],


    'attendance' => [
        'all' => 'Show Attendance employee',
        'absence' => 'Absence',
        'official_vacation' => 'Official Vacation',
        'status' => 'Enter Permission Status',
    ],

    'points'=>[
        'all'=>'show all points'
    ],

    'notifications'=>[
        'all'=>'show Notifications list'
    ],

    'permissions'=>[
        'all'=>'show permissions list',
        'send_permission' => 'Send Request Permission To clinic successfully',
        'permission_id' => 'permission Type Required',
        'reason'=>'Reason Is Required',
    ],

    'employees' => [
        'all' => 'عرض كل الموظفيين',
        'days' => 'عرض ايام الاسبوع',
        'doctor_degrees' => 'عرض الدرجة الوظيفيه للاطباء',
        'degree_id' => ' الدرجة الوظيفيه للطبيب مطلوبة',
        'gender' => 'نوع الجنس مطلوب',
        'specialist_ids' => 'التخصص مطلوب',
        'app_type' => 'قسم الموظف مطلوب',
        'id'=>'برجاء ارسال رقم الموظف والتاكد من الرقم',
        'delete'=>'تم حذف البيانات بنجاح',
        'permissions'=>'Show all permissions',
        'permission_ids'=>'permission ids required and min one permission at least',
    ],

    'data'=>'Show Data Successfully',

    'userApp' => [
        'clinics' => 'Show All Clinics',
        'clinic' => [
            'id'=>'clinic id required'
        ],
    ],

    'complaints_box' => [
        'all' => 'Show Complains Box',
        'reply' => 'Reply on Complain Required',
        'send' => 'Send Complain Successfully',
        'id' => 'Complain Id Required'
    ],

    'packages' => [
        'all' => 'عرض الباقات',
        'reply' => 'الرد على رسالة العميل مطلوب',
        'subscribe' => 'تم اشتراكك فى الباقة بنجاح',
        'package_id'=>'برجاء ارسال رقم الباقة'
    ],

    'doctors'=>[
        'id'=>'Doctor id required',
        'appointment_time'=>'Appointment Time required',
        'send_question'=>'تم ارسال سوالك الى الطبيب بنجاح',
        'send_reservation'=>'Send Reservation To Doctor Successfully',
        'cancel_reservation'=>'Cancel Reservation Successfully',
        'check_reservation_one'=>'You have used the service once. To benefit from the service, you can subscribe to a package',
        'subscribe_package'=>'You must subscribe to take advantage of this service!',
        'reservation_exist'=>'Another appointment has been booked before. If you want to change the appointment, please cancel the previous appointment',
        'another_reservation_exist'=>'You cannot book another appointment with the same doctor until after two weeks have passed because you did not adhere to the appointments',
    ],

    'reservation'=>[
        'all'=>'كل الحجوزات'
    ],
    'chat'=>[
        'send_message'=>'Send Message Successfully',
        'details'=>'Chat Details',
    ]


];
