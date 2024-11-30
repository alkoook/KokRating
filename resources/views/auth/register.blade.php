<!DOCTYPE html>
<html>
<head>
	<title>Kok Rating</title>
	<link rel="stylesheet" type="text/css" href="slide navbar style.css">
<style>
        body{
	margin: 0;
	padding: 0;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 100vh;
	font-family: 'Jost', sans-serif;
	background: linear-gradient(to bottom, #0f0c29, #302b63, #24243e);
    }
    .main{
        width: 350px;
        height:500px;
        background: red;
        overflow: hidden;
        background: url("https://doc-08-2c-docs.googleusercontent.com/docs/securesc/68c90smiglihng9534mvqmq1946dmis5/fo0picsp1nhiucmc0l25s29respgpr4j/1631524275000/03522360960922298374/03522360960922298374/1Sx0jhdpEpnNIydS4rnN4kHSJtU1EyWka?e=view&authuser=0&nonce=gcrocepgbb17m&user=03522360960922298374&hash=tfhgbs86ka6divo3llbvp93mg4csvb38") no-repeat center/ cover;
        border-radius: 10px;
        box-shadow: 5px 20px 50px #000;
    }
    #chk{
        display: none;
    }
    .signup{
        position: relative;
        width:100%;
        height: 100%;
    }
    label{
        color: #fff;
        font-size: 1.7rem;
        justify-content: center;
        display: flex;
        margin: 50px;
        font-weight: bold;
        cursor: pointer;
        transition: .5s ease-in-out;
    }
    input{
        width: 60%;
        height: 10px;
        background: #e0dede;
        justify-content: center;
        display: flex;
        margin: 10px auto;
        padding: 12px;
        border: none;
        outline: none;
        border-radius: 5px;
    }
    button{
        width: 60%;
        height: 40px;
        margin: 5px auto;
        justify-content: center;
        display: block;
        color: #fff;
        background: #573b8a;
        font-size: 1em;
        font-weight: bold;
        outline: none;
        border: none;
        border-radius: 5px;
        transition: .2s ease-in;
        cursor: pointer;
    }
    button:hover{
        background: #6d44b8;
    }
    .login{
        height: 460px;
        background: #eee;
        border-radius: 60% / 10%;
        transform: translateY(-180px);
        transition: .8s linear;
    }
    .login label{
        color: #573b8a;
        transform: scale(.6);
    }

    #chk:checked ~ .login{
        transform: translateY(-500px);
    }
    #chk:checked ~ .login label{
        transform: scale(1);
    }
    #chk:checked ~ .signup label{
        transform: scale(.6);
    }

    .inpu {
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease, border-color 0.3s ease;
    }

    /* عند وجود خطأ */
    .inpu.is-invalid {
        border: 1px solid #0615df;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(3, 26, 199, 0.8);
        border-color: rgb(56, 6, 236);
    }

    .inp {
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease, border-color 0.3s ease;
    }

    /* عند وجود خطأ */
    .inp.is-invalid {
        border: 1px solid #0615df;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(3, 26, 199, 0.8);
        border-color: rgb(56, 6, 236);
    }

</style>

</head>
<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">


        <div class="signup">
            <form action="{{route('reg')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="chk" aria-hidden="true">Sign up</label>
    <!-- Name Field -->
        <input
        type="text"
        name="name"
        placeholder="@error('name') {{ $message }} @else  Name @enderror"
        value="{{ old('name') }}"
        class="inpu @error('name') is-invalid @enderror">

    <!-- Email Field -->
    <input
        type="email"
        name="email"
        placeholder='@error('email') {{ $message }} @else Email @enderror'
        class="inpu @error('email') is-invalid @enderror">

    <!-- Password Field -->
    <input
        type="password"
        name="password"
        placeholder="@error('password') {{ $message }} @else Password @enderror"
        class="inpu @error('password') is-invalid @enderror">

    <!-- Phone Field -->
    <input
        type="text"
        name="phone"
        placeholder="@error('phone') {{ $message }} @else Phone @enderror"
        value="{{ old('phone') }}"
        class="inpu @error('phone') is-invalid @enderror">



                <div class="custom-file-upload" style="margin: 10px;">
                    <span id="file-name" style="color:rgb(249, 249, 249);width:40%;white-space:nowrap;padding:5px;" class="inpu @error('image')is-invalid  @enderror">Choose an image</span>
                    <button type="button" id="upload-button" class="btn btn-primary">Upload</button>
                    <input type="file" id="series-image" name="image" class="form-control" style="display: none;">
                </div>
                <div id="file-name" style="margin: 10px; color: white;"></div>

                <button>Sign up</button>
            </form>
        </div>


        <div class="login">

            <form action="{{route('login')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="chk" aria-hidden="true">Login</label>
                @if ($errors->has('message'))
                <div style="position: relative;left:25%;width:50%;border: 1px solid #ed5b5b;border-radius: 10px;     box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease, border-color 0.3s ease;color:#d60707;padding:4px;">
                    {{ $errors->first('message') }}
                </div>
            @endif
 <!-- Email Field -->
    <input
    type="email"
    name="em"
    placeholder='@error('em') {{ $message }} @else Email @enderror'
    class="inp @error('em') is-invalid @enderror">

<!-- Password Field -->
<input
    type="password"
    name="pass"
    placeholder="@error('pass') {{ $message }} @else Password @enderror"
    class="inp @error('pass') is-invalid @enderror">
                <button>Login</button>
            </form>
        </div>
    </div>

    <script>
        // استدعاء نافذة اختيار الملف عند الضغط على الزر
        document.getElementById('upload-button').addEventListener('click', function () {
            document.getElementById('series-image').click();
        });

        // عرض اسم الملف عند اختياره
        document.getElementById('series-image').addEventListener('change', function () {
            const fileName = this.files[0] ? this.files[0].name : 'Choose an image';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
</body>
</html>
