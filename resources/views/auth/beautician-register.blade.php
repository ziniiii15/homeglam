<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautician Register | HomeGlam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary: #D63384;
            --primary-soft: #ffe5f0;
            --bg-pink: #FFF5F7;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --radius: 24px;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-pink);
            overflow-x: hidden;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            background: radial-gradient(circle at top right, #ffe5f0, #ffffff 60%);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(214, 51, 132, 0.1);
            border-radius: var(--radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            padding: 3rem;
            width: 100%;
            max-width: 600px;
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-logo a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 800;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .brand-logo i {
            color: var(--primary);
            font-size: 2.2rem;
        }

        h3 {
            color: var(--text-dark);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .subtitle {
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
        }

        .form-label {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.6rem;
            font-size: 0.9rem;
        }

        .input-group-text {
            background: transparent;
            border: 1.5px solid #eee;
            border-right: none;
            color: var(--text-muted);
            border-radius: 14px 0 0 14px;
        }

        .form-control {
            background: transparent;
            border: 1.5px solid #eee;
            border-left: none;
            color: var(--text-dark);
            padding: 12px 16px 12px 0;
            border-radius: 0 14px 14px 0;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: transparent;
            border-color: var(--primary);
            box-shadow: none;
            color: var(--text-dark);
        }
        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* For inputs without groups */
        .form-control-simple {
            border-left: 1.5px solid #eee;
            border-radius: 14px;
            padding-left: 16px;
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(214, 51, 132, 0.2);
        }

        .btn-submit:hover {
            background: #b02a6c;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(214, 51, 132, 0.3);
            color: white;
        }

        .auth-link {
            text-align: center;
            margin-top: 2rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .auth-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .section-divider {
            border-top: 1px solid #eee;
            margin: 2rem 0;
            position: relative;
        }
        .section-divider span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--glass-bg);
            padding: 0 1rem;
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 576px) {
            .glass-card { padding: 2rem; }
        }
    </style>
</head>
<body>

    <div class="auth-wrapper">
        <div class="glass-card">
            <div class="brand-logo">
                <a href="{{ route('welcome') }}">
                    <i class="bi bi-gem"></i> HomeGlam
                </a>
            </div>
            
            <h3>Professional Registration</h3>
            <p class="subtitle">Join our elite network of beauty experts.</p>

            <form method="POST" action="{{ route('beautician.register') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" required placeholder="Jane Doe">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" required placeholder="jane@example.com">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-right: 1.5px solid #eee; border-radius: 14px 0 0 14px;">+63</span>
                        <input type="text" name="phone_number" class="form-control" placeholder="9xxxxxxxxx" maxlength="10" pattern="[9][0-9]{9}" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 10) this.value = this.value.slice(0, 10);" style="border-left: none;">
                    </div>
                </div>

                <div class="section-divider"><span>Professional Details</span></div>

                <input type="hidden" name="address" id="fullAddress">
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" id="addrRegion" class="form-control form-control-simple mb-2" placeholder="Region" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="addrProvince" class="form-control form-control-simple mb-2" placeholder="Province" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="addrCity" class="form-control form-control-simple mb-2" placeholder="City / Municipality" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="addrBarangay" class="form-control form-control-simple mb-2" placeholder="Barangay" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="addrPostal" class="form-control form-control-simple mb-2" placeholder="Postal Code" required>
                    </div>
                    <div class="col-md-8">
                        <input type="text" id="addrStreet" class="form-control form-control-simple mb-2" placeholder="Street Name" required>
                    </div>
                    <div class="col-12">
                        <input type="text" id="addrHouse" class="form-control form-control-simple" placeholder="House Number / Building" required>
                    </div>
                </div>

                <div class="mt-3 mb-3">
                    <label class="form-label">Verification Document (ID/Certificate)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-file-earmark-arrow-up"></i></span>
                        <input type="file" name="verification_document" class="form-control" required>
                    </div>
                    <small class="text-muted">Please upload a valid ID or professional certificate.</small>
                </div>

                <div class="section-divider"><span>Security</span></div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" required placeholder="••••••••">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-check"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Register as Pro</button>
            </form>

            <div class="auth-link">
                Already have an account? <a href="{{ route('beautician.login') }}">Login</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateAddress = () => {
                const region = document.getElementById('addrRegion').value || '';
                const province = document.getElementById('addrProvince').value || '';
                const city = document.getElementById('addrCity').value || '';
                const barangay = document.getElementById('addrBarangay').value || '';
                const postal = document.getElementById('addrPostal').value || '';
                const street = document.getElementById('addrStreet').value || '';
                const house = document.getElementById('addrHouse').value || '';
                
                const parts = [region, province, city, barangay, postal, street, house];
                const fullAddr = parts.filter(p => p !== '').join(', ');
                document.getElementById('fullAddress').value = fullAddr;
            };

            ['addrRegion', 'addrProvince', 'addrCity', 'addrBarangay', 'addrPostal', 'addrStreet', 'addrHouse'].forEach(id => {
                const el = document.getElementById(id);
                if(el) el.addEventListener('input', updateAddress);
            });
        });
    </script>

</body>
</html>
