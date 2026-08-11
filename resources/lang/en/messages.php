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
        'login_message_failed' => 'incorrect Data Please Try Again',
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
        'qr_code_upload' => 'QR Code',
        'newPassword' => 'newPassword is Required',


        'edit_profile' => 'Profile Updated Successfully',
        'phone_another_account' => 'Phone Already Exist Another Account',
        'email_another_account' => 'Email Exist Another Account',


        'code' => 'Please Enter Verification Code',

        'active_code' => 'Code Active Successfully',
        'incorrect_data' => 'phone  is not valid',
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

    'complaints_box' => [
        'all' => 'Show Complains Box',
        'reply' => 'Reply on Complain Required',
        'send_reply' => 'Send Reply on Complain Successfully',
        'id' => 'Complain Id Required'
    ],

    'accounts' => [
        'phone' => 'رقم الهاتف',
        'email' => 'البريد الالكترونى',
    ],

    'posts' => [
        'all' => 'Show Clinic Posts',
        'content' => 'Content Post Required',
        'image' => 'Image Post Required',
        'id' => 'post Id Required',
        'delete' => 'Delete Post Successfully',
    ],
    'Added' => 'Data Added Successfully',
    'updated' => 'Data Updated Successfully',
    'deleted' => 'Data Deleted Successfully',
    'update_status' => 'Change Status Data Successfully',

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
        'check_in' => 'Attendance has been successfully completed',
        'check_out' => 'Departure has been completed successfully',
        'no_work' => 'Sorry, this day is not registered for work for you. It may be an official holiday. Please refer to the administration to confirm',
        'attendance_work' =>'Please wait until your scheduled Shift time begins ',
        'attendance_already' => 'You cannot make a Leaving until after the time allowed by the administration',
        'check_attendance_time' =>'You cannot make an attendance for exceeding the time allowed by the administration, please refer to them',


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
        'clinics' => 'Show clinics',
        'absence' => 'غياب',
        'official_vacation' => 'اجازة رسمية',
        'status' => 'ادخل حاله الاذن',
    ],

    'rating' => [
        'send' => 'Send Rating Successfully',
    ],

    // rate_value
    'reservation_id_required'   => 'Reservation ID is required',
    'reservation_id_not_found'  => 'Reservation does not exist',

    'rate_required' => 'Rating is required',
    'rate_integer'  => 'Rating must be an integer',
    'rate_min'      => 'Minimum rating is 1',
    'rate_max'      => 'Maximum rating is 5',

    'comment_string' => 'Comment must be a string',



    'reservation_not_found' => 'Reservation not found',
    'reservation_already_rated' => 'Reservation already rated',
    'rating_submitted_successfully' => 'Rating submitted successfully',

    'reservation' => [
        'completed_success' => 'Reservation completed successfully',
        'cannot_complete' => 'Cannot complete reservation in its current status',
    ],

];
