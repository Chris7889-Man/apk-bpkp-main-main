<x-guest-layout>

    <style>
        .form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            background-color: #ffffff;
            padding: 30px;
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        ::placeholder {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .form button {
            align-self: flex-end;
        }

        .flex-column > label {
            color: #151717;
            font-weight: 600;
            font-size: 14px;
        }

        .inputForm {
            border: 2px solid #9ca3af;
            border-radius: 10px;
            height: 50px;
            display: flex;
            align-items: center;
            padding-left: 12px;
            padding-right: 12px;
            transition: 0.2s ease-in-out;
            background: #f9fafb;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.06);
        }

        .input {
            margin-left: 10px;
            border-radius: 10px;
            border: none;
            width: 100%;
            height: 100%;
            font-size: 14px;
        }

        .input:focus {
            outline: none;
            box-shadow: none;
        }

        .inputForm:focus-within {
            border: 2px solid #2d79f3;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(45, 121, 243, 0.15);
        }

        .input {
            background: transparent;
        }

        .input:-webkit-autofill,
        .input:-webkit-autofill:hover,
        .input:-webkit-autofill:focus {
            -webkit-text-fill-color: #151717;
            -webkit-box-shadow: 0 0 20 1000px #ffffff inset;
            box-shadow: 0 0 5 1000px #ffffff inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        .flex-row {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            justify-content: space-between;
        }

        .flex-row > div > label {
            font-size: 14px;
            color: #151717;
            font-weight: 400;
            cursor: pointer;
        }

        .span {
            font-size: 14px;
            margin-left: 5px;
            color: #2d79f3;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
        }

        .span:hover {
            text-decoration: underline;
        }

        .button-submit {
            margin: 15px 0 5px 0;
            background-color: #151717;
            border: 1px solid #9ca3af;
            color: white;
            font-size: 15px;
            font-weight: 500;
            border-radius: 10px;
            height: 50px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .button-submit:hover {
            background-color: #252727;
        }

        .p {
            text-align: center;
            color: #151717;
            font-size: 14px;
            margin: 5px 0;
        }

        .p.line {
            display: flex;
            align-items: center;
            color: #6b7280;
            font-size: 13px;
        }

        .p.line::before, .p.line::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
            margin: 0 10px;
        }

        .btn {
            margin-top: 5px;
            width: 100%;
            height: 46px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 500;
            font-size: 14px;
            gap: 10px;
            border: 1px solid #9ca3af;
            background-color: white;
            cursor: pointer;
            transition: 0.2s ease-in-out;
            text-decoration: none;
            color: #151717;
        }

        .btn:hover {
            border: 1px solid #2d79f3;
        }

        /* From Uiverse.io by DaniloMGutavo */
        .checkbox-container {
            display: inline-block;
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            font-size: 16px;
            user-select: none;
        }

        .custom-checkbox {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
            border-radius: 4px;
            transition: background-color 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        .custom-checkbox:checked ~ .checkmark {
            background-color: #2196F3;
            box-shadow: 0 3px 7px rgba(33, 150, 243, 0.3);
        }

        .custom-checkbox:checked ~ .checkmark:after {
            display: block;
        }

        @keyframes checkAnim {
            0% {
                height: 0;
            }

            100% {
                height: 10px;
            }
        }

        .custom-checkbox:checked ~ .checkmark:after {
            animation: checkAnim 0.2s forwards;
        }
    </style>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-8">
        <form class="form" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Logo & Title di dalam form -->
            <div class="flex flex-col items-center justify-center gap-2 pb-2 border-b border-gray-100">
                <img src="{{ asset('images/logo-bpkp.png') }}" alt="Logo BPKP" class="w-24 h-auto">
                <h2 class="text-base font-bold text-gray-800 text-center">Aplikasi Konsultasi Online</h2>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-2 text-sm" :status="session('status')" />

            <!-- Email Field -->
            <div class="flex-column">
                <label for="email">Email</label>
            </div>
            <div class="inputForm">
                <svg height="20" viewBox="0 0 32 32" width="20" xmlns="http://www.w3.org/2000/svg" fill="#6b7280">
                    <g id="Layer_3" data-name="Layer 3">
                        <path d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"></path>
                    </g>
                </svg>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="input" placeholder="Enter your Email" required autofocus autocomplete="username">
            </div>
            <x-input-error :messages="$errors->get('email')" class="text-xs text-red-500 mt-0" />

            <!-- Password Field -->
            <div class="flex-column">
                <label for="password">Password</label>
            </div>
            <div class="inputForm relative">
                <svg height="20" viewBox="-64 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg" fill="#6b7280">
                    <path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"></path>
                    <path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"></path>
                </svg>
                <input type="password" id="password" name="password" class="input pr-8" placeholder="Enter your Password" required autocomplete="current-password">
                <button type="button" onclick="togglePassword()" class="absolute right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none bg-transparent border-0 cursor-pointer" aria-label="Tampilkan password">
                    <svg viewBox="0 0 576 512" height="18px" width="18px" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="text-xs text-red-500 mt-0" />

            <!-- Remember me & Forgot Password -->
            <div class="flex-row">
                <label class="checkbox-container">
                    <input class="custom-checkbox" type="checkbox" id="remember_me" name="remember" value="1">
                    <span class="checkmark"></span>
                    <span class="text-sm text-gray-700 ml-2" style="position: relative; left: 10px; top: 2px;">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="span">Forgot password?</a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="button-submit">Sign In</button>

            <!-- Register Link -->
            @if (Route::has('register'))
                <p class="p">Don't have an account? <a href="{{ route('register') }}" class="span">Sign Up</a></p>
            @endif

            <p class="p line">Or With</p>

            <!-- Social Logins (Visual) -->
            <div class="flex-row">
                <button type="button" class="btn google">
                    <svg version="1.1" width="18" height="18" id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path style="fill:#FBBB00;" d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456C103.821,274.792,107.225,292.797,113.47,309.408z"></path>
                        <path style="fill:#518EF8;" d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176L507.527,208.176z"></path>
                        <path style="fill:#28B446;" d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z"></path>
                        <path style="fill:#F14336;" d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0C318.115,0,375.068,22.126,419.404,58.936z"></path>
                    </svg>
                    Google
                </button>
                <button type="button" class="btn apple">
                    <svg height="18" width="18" id="Capa_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22.773 22.773" fill="currentColor">
                        <path d="M15.769,0c0.053,0,0.106,0,0.162,0c0.13,1.606-0.483,2.806-1.228,3.675c-0.731,0.863-1.732,1.7-3.351,1.573 c-0.108-1.583,0.506-2.694,1.25-3.561C13.292,0.879,14.557,0.16,15.769,0z"></path>
                        <path d="M20.67,16.716c0,0.016,0,0.03,0,0.045c-0.455,1.378-1.104,2.559-1.896,3.655c-0.723,0.995-1.609,2.334-3.191,2.334 c-1.367,0-2.275-0.879-3.676-0.903c-1.482-0.024-2.297,0.735-3.652,0.926c-0.155,0-0.31,0-0.462,0 c-0.995-0.144-1.798-0.932-2.383-1.642c-1.725-2.098-3.058-4.808-3.306-8.276c0-0.34,0-0.679,0-1.019 c0.105-2.482,1.311-4.5,2.914-5.478c0.846-0.52,2.009-0.963,3.304-0.765c0.555,0.086,1.122,0.276,1.619,0.464 c0.471,0.181,1.06,0.502,1.618,0.485c0.378-0.011,0.754-0.208,1.135-0.347c1.116-0.403,2.21-0.865,3.652-0.648 c1.733,0.262,2.963,1.032,3.723,2.22c-1.466,0.933-2.625,2.339-2.427,4.74C17.818,14.688,19.086,15.964,20.67,16.716z"></path>
                    </svg>
                    Apple
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    </script>

</x-guest-layout>
