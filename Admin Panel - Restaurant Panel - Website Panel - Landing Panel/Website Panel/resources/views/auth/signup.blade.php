@include('auth.default')


<?php
$countries = file_get_contents(asset('countriesdata.json'));
$countries = json_decode($countries);
$countries = (array) $countries;
$newcountries = array();
$newcountriesjs = array();
foreach ($countries as $keycountry => $valuecountry) {
    $newcountries[$valuecountry->phoneCode] = $valuecountry;
    $newcountriesjs[$valuecountry->phoneCode] = $valuecountry->code;
}
?>

<link href="{{ asset('vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('/css/font-awesome.min.css')}}" rel="stylesheet">
<div class="siddhi-signup login-page vh-100">


    <div class="d-flex align-items-center justify-content-center py-3">

        <div class="col-md-6">

            <div class="col-10 mx-auto card p-3">

                <h3 class="text-dark my-0 mb-3">{{trans('lang.sign_up_with_us')}}</h3>

                <p class="text-50">{{trans('lang.sign_up_to_continue')}}</p>

                <div class="error" style="color: red" id="field_error"></div>

                <div class="error" id="field_error1" style="color:red;display:none;"></div>

                <form class="mt-3 mb-4" action="javascript:void(0)" onsubmit="return signupClick()">


                    <div class="form-group" id="firstName_div">

                        <label for="firstName" class="text-dark">{{trans('lang.first_name')}}</label>

                        <input type="text" placeholder="Enter FirstName" class="form-control" id="firstName" required>
                        <input type="hidden" id="hidden_fName" />
                    </div>

                    <div class="form-group" id="lastName_div">

                        <label for="lastName" class="text-dark">{{trans('lang.last_name')}}</label>

                        <input type="text" placeholder="Enter LastName" class="form-control" id="lastName" required>
                        <input type="hidden" id="hidden_lName" />
                    </div>

                    <div class="form-group" id="email_div">

                        <label for="email" class="text-dark">{{trans('lang.email_address')}}</label>

                        <input type="email" placeholder="Enter Email Address" class="form-control" id="email" required
                            autocomplete="new-password" >

                    </div>

                    {{--<div class="form-group">

                        <label for="mobileNumber" class="text-dark">{{trans('lang.mobile_number')}}</label>

                        <input type="text" placeholder="Enter Mobile" class="form-control" id="mobileNumber"
                            pattern="[0-9]+" minlength="4" maxlength="15" required>

                    </div>--}}


                    <div class="form-group" id="phone-box">
                        <div class="col-xs-12">

                            <select name="country" id="country_selector">
                                <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                    <?php $selected = ""; ?>
                                    <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>"
                                        value="<?php echo $keycy; ?>">+<?php echo $valuecy->phoneCode; ?>  {{$valuecy->countryName}}</option>
                                <?php } ?>
                            </select>
                            <input class="form-control" placeholder="{{trans('lang.user_phone')}}" id="mobileNumber"
                                type="number" name="mobileNumber" value="{{ old('mobileNumber') }}" required
                                autocomplete="mobileNumber">
                        </div>

                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group" id="pass_div">

                        <label for="password" class="text-dark">{{trans('lang.password')}}</label>

                        <input type="password" placeholder="Enter Password" class="form-control" id="password"
                            minlength="8" required autocomplete="new-password">

                    </div>

                    <div class="form-group" id="referral_div">

                        <label for="referral_code" class="text-dark">{{trans('lang.referral_code')}}
                            ({{trans('lang.optional')}})</label>

                        <input type="text" placeholder="Enter Referral Code" class="form-control" id="referral_code">
                        <input type="hidden" id="hidden_referral" />
                    </div>

                    <div class="form-group">

                        <input type="hidden" name="email_valid" id="email_valid" value="1">

                    </div>
                    <div class="form-group " id="otp-box" style="display:none;">
                        <input class="form-control" placeholder="{{trans('lang.otp')}}" id="verificationcode"
                            type="text" class="form-control" name="otp" value="{{ old('otp') }}" autocomplete="otp">
                        <div class="otp_error">

                        </div>
                    </div>
                    <div id="recaptcha-container" style="display:none;"></div>


                    <button type="submit" class="btn btn-primary btn-lg btn-block btn-sign-up" id="btn-sign-up">

                        {{trans('lang.sign_up')}}

                    </button>

                    <button type="button" style="display:none;" onclick="applicationVerifier()" id="verify_btn"
                        class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{trans('lang.otp_verify')}}
                    </button>

                      <button type="button" class="btn btn-primary btn-lg btn-block btn-sign-up" onclick="sendOTP()"
                        id="send-code" style="display:none">

                        {{trans('lang.otp_send')}}

                    </button>

                </form>

                <div class="or-line mb-4">
                    <span>OR</span>
                </div>

                <div class="new-acc d-flex align-items-center justify-content-center">

                    <a href="#" class="btn btn-primary" id="btn-signup-phone" onclick="signupWithPhone()">

                        <i class="fa fa-phone"> </i> {{trans('lang.sinup_with_phone')}}

                    </a>

                </div>
                <div class="new-acc d-flex align-items-center justify-content-center">

                    <a href="#" class="btn btn-primary" id="btn-signup-email" onclick="signupWithEmail()"
                        style="display:none">

                        <i class="fa fa-envelope"> </i> {{trans('lang.signup_with_email')}}

                    </a>

                </div>


            </div>

            <div class="new-acc d-flex align-items-center justify-content-center mt-4 mb-3">

                <a href="{{url('login')}}">

                    <p class="text-center m-0"> {{trans('lang.already_an_account')}} {{trans('lang.sign_in')}}</p>

                </a>

            </div>

        </div>

    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>

<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-database.js"></script>
<script src="{{ asset('js/crypto-js.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>

<script type="text/javascript">

    var createdAtman = firebase.firestore.Timestamp.fromDate(new Date());

    var database = firebase.firestore();

    async function signupClick() {

        $(".btn-sign-up").text('Please wait...');
        var email = $("#email").val();
        var password = $("#password").val();
        var mobileNumber = '+' + jQuery("#country_selector").val() + '' + jQuery("#mobileNumber").val();

        var firstName = $("#firstName").val();

        var lastName = $("#lastName").val();

        var referralCode = $("#referral_code").val();

        var referralBy = '';
        if (referralCode) {
            var referralByRes = getReferralUserId(referralCode);
            var referralBy = await referralByRes.then(function (refUserId) {
                return refUserId;
            });
        }

        var userReferralCode = Math.floor(Math.random() * 899999 + 100000);
        userReferralCode = userReferralCode.toString();

        firebase.auth().createUserWithEmailAndPassword(email, password)

            .then((userCredential) => {

                var uuid = userCredential.user.uid;

                database.collection("referral").doc(uuid).set({
                    'id': uuid,
                    'referralBy': referralBy ? referralBy : '',
                    'referralCode': userReferralCode,
                });

                database.collection("users").doc(uuid).set({
                    'appIdentifier':"web",
                    'email': email,
                    'firstName': firstName,
                    'lastName': lastName,
                    'id': uuid,
                    'phoneNumber': mobileNumber,
                    'role': "customer",
                    'profilePictureURL': "",
                    'createdAt': createdAtman,
                    'active':true
                })

                    .then(() => {

                        firebase.auth().signInWithEmailAndPassword(email, password).then(function (result) {

                            var url = "{{route('newRegister')}}";

                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    userId: uuid,
                                    email: email,
                                    password: password,
                                    firstName: firstName,
                                    lastName: lastName
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },

                                success: function (data) {

                                    if (data.access) {

                                        window.location = "{{url('/')}}";
                                    }
                                }

                            })

                        })

                    })

                    .catch((error) => {

                        console.error("Error writing document: ", error);

                        $("#field_error").html(error);
                        window.scrollTo(0, 0);

                    });


            })

            .catch((error) => {

                var errorCode = error.code;

                var errorMessage = error.message;

                $("#field_error").html(errorMessage);
                window.scrollTo(0, 0);

                $(".btn-sign-up").text("{{trans('lang.sign_up')}}");

            });
        return false;
    }

    async function getReferralUserId(referralCode) {
        var refUserId = database.collection('referral').where('referralCode', '==', referralCode).get().then(async function (snapshots) {
            if (snapshots.docs.length > 0) {
                var referralData = snapshots.docs[0].data();
                return referralData.id;
            }
        });
        return refUserId;
    }

    var newcountriesjs = '<?php echo json_encode($newcountriesjs); ?>';
    var newcountriesjs = JSON.parse(newcountriesjs);

    function formatState(state) {

        if (!state.id) {
            return state.text;
        }
        var baseUrl = "<?php echo URL::to('/'); ?>/flags/120/";
        var $state = $(
            '<span><img src="' + baseUrl + '/' + newcountriesjs[state.element.value].toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
        );
        return $state;
    }

    function formatState2(state) {
        if (!state.id) {
            return state.text;
        }

        var baseUrl = "<?php echo URL::to('/'); ?>/flags/120/"
        var $state = $(
            '<span><img class="img-flag" /> <span></span></span>'
        );
        $state.find("span").text(state.text);
        $state.find("img").attr("src", baseUrl + "/" + newcountriesjs[state.element.value].toLowerCase() + ".png");

        return $state;
    }

    jQuery(document).ready(function () {

        jQuery("#country_selector").select2({
            templateResult: formatState,
            templateSelection: formatState2,
            placeholder: "Select Country",
            allowClear: true
        });

    });

    function signupWithPhone() {
        $('#email_div').hide();
        $('#pass_div').hide();
        $('#btn-signup-phone').hide();
        $('#btn-sign-up').hide();
        jQuery("#recaptcha-container").show();
        $('#send-code').show();
        $('#btn-signup-email').show();
        jQuery("#otp-box").hide();
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'invisible',
            'callback': (response) => {
            
            }
        });
    }
    function signupWithEmail() {
        $('#firstName_div').show();
        $('#lastName_div').show();
        $('#phone-box').show();
        $('#email_div').show();
        $('#pass_div').show();
        $('#referral_div').show();
        $('#btn-signup-phone').show();
        $('#btn-sign-up').show();
        $('#send-code').hide();
        $('#verify_btn').hide();
        jQuery("#otp-box").hide();
        $('#verificationcode').attr('required', 'false');
        $('#btn-signup-email').hide();
    }
    function sendOTP() {
        var firstName = $('#firstName').val();
        var lastName = $('#lastName').val();
        var referral = $('#referral_code').val();

        if(firstName == ""){
            
            $("#field_error1").css('display','block');
            $("#field_error1").html("");
            jQuery("#field_error1").html("Please enter first name");
        }
        else if(lastName == ""){
            $("#field_error1").css('display','block');
            $("#field_error1").html("");
            jQuery("#field_error1").html("Please enter last name");
        }
        else if ($("#mobileNumber").val() == ""){
            $("#field_error1").css('display','block');
            $("#field_error1").html("");
            jQuery("#field_error1").html("Please enter phonenumber");  
        }
        else if (jQuery("#mobileNumber").val() && jQuery("#country_selector").val()) {
            $("#field_error1").css('display','none');
            var phoneNumber = '+' + jQuery("#country_selector").val() + jQuery("#mobileNumber").val();
            
                database.collection("users").where('phoneNumber', '==', phoneNumber).get().then(async function (snapshots) {
                    if (snapshots.docs.length > 0) {
                        alert('You already have account with this phone number')
                        return false;
                    } else {
                        $('#hidden_fName').val(firstName);
                        $('#hidden_lName').val(lastName);
                        $('#hidden_referral').val(referral);
                        firebase.auth().signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier)
                            .then(function (confirmationResult) {
                                window.confirmationResult = confirmationResult;
                                if (confirmationResult.verificationId) {
                                    $('#firstName_div').hide();
                                    $('#lastName_div').hide();
                                    $('#email_div').hide();
                                    $('#pass_div').hide();
                                    $('#phone-box').hide();
                                    $('#referral_div').hide();
                                    $('#btn-signup-phone').hide();
                                    $('#btn-sign-up').hide();
                                    $('#send-code').show();
                                    $('#btn-signup-email').show();
                                    jQuery("#recaptcha-container").hide();
                                    jQuery("#otp-box").show();
                                    $('#verificationcode').attr('required', 'true');
                                    jQuery("#verify_btn").show();
                                }
                            }).catch((error) => {

                            console.error("Error writing document: ", error);
                            $("#field_error").html(error);
                            window.scrollTo(0, 0);

                        });
                    }
                })


        }
    }

    function applicationVerifier() {
        var code = $('#verificationcode').val();
        if (code == "") {
            $('.otp_error').html('Please Enter OTP')
        } else {
            window.confirmationResult.confirm(document.getElementById("verificationcode").value)
                .then(async function (result) {
                    var mobileNumber = result.user.phoneNumber;
                    var firstName = $('#hidden_fName').val();
                    var lastName = $('#hidden_lName').val();

                    var password = "";
                    var referralCode = $('#hidden_referral').val();
                    var referralBy = '';
                    if (referralCode) {
                        var referralByRes = getReferralUserId(referralCode);
                        var referralBy = await referralByRes.then(function (refUserId) {
                            return refUserId;
                        });
                    }

                    var userReferralCode = Math.floor(Math.random() * 899999 + 100000);
                    userReferralCode = userReferralCode.toString();
                    var uuid = result.user.uid;

                    database.collection("referral").doc(uuid).set({
                        'id': uuid,
                        'referralBy': referralBy ? referralBy : '',
                        'referralCode': userReferralCode,
                    });
                    database.collection("users").doc(uuid).set({
                        'appIdentifier':"web",
                        'email': "",
                        'firstName': firstName,
                        'lastName': lastName,
                        'id': uuid,
                        'phoneNumber': mobileNumber,
                        'role': "customer",
                        'profilePictureURL': "",
                        'createdAt': createdAtman,
                        'active':true
                    }).then(() => {
                        var url = "{{route('newRegister')}}";

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                userId: uuid,
                                email: mobileNumber,
                                password: password,
                                firstName: firstName,
                                lastName: lastName
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },

                            success: function (data) {

                                if (data.access) {

                                    window.location = "{{url('/')}}";
                                }
                            }

                        })
                    }).catch((error) => {

                        console.error("Error writing document: ", error);
                        $("#field_error").html(error);
                        window.scrollTo(0, 0);

                    });


                }).catch((error) => {

                    $("#otp_error").html("OTP Verification Failed");

                });
        }

    }



</script>