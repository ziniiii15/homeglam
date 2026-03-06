<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautician Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary: #D63384;
            --primary-light: #ff85c0;
            --accent: #FFA6C9;
            --text-light: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.15);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* Background */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset("bg1.png") }}') no-repeat center center;
            background-size: cover;
            z-index: -2;
            transform: scale(1.05);
            animation: slowZoom 20s infinite alternate;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(20, 5, 10, 0.7) 50%, rgba(50, 10, 30, 0.6) 100%);
            z-index: -1;
        }

        /* Container */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            position: relative;
            z-index: 1;
        }

        /* Glass Card */
        .glass-card {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            padding: 3rem;
            width: 100%;
            max-width: 550px; /* Slightly wider for register form */
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* Form Elements */
        h3 {
            color: white;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: -0.5px;
        }

        .form-label {
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(214, 51, 132, 0.15);
            color: white;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Button */
        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(214, 51, 132, 0.3);
        }

        .btn-submit:hover {
            background: #c0206d;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(214, 51, 132, 0.5);
            color: white;
        }

        /* Links */
        .auth-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .auth-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-link a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        /* Animations */
        @keyframes slowZoom {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .glass-card {
                padding: 2rem;
            }
            h3 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>

    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="auth-container">
        <div class="glass-card">
            <h3>Beautician Register</h3>

            <form method="POST" action="{{ route('beautician.register') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <div class="input-group">
                        <span class="input-group-text bg-secondary text-white border-secondary">+63</span>
                        <input type="text" id="phone" name="phone_number" class="form-control" placeholder="9xxxxxxxxx" maxlength="10" pattern="[9][0-9]{9}" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 10) this.value = this.value.slice(0, 10);">
                    </div>
                </div>

                <input type="hidden" name="address" id="fullAddress">
                <div class="mb-3">
                    <label class="form-label">Address Details</label>
                    <div class="row g-2">
                        <div class="col-12">
                            <input type="text" id="addrRegion" class="form-control mb-2" placeholder="Region" required>
                        </div>
                        <div class="col-12">
                            <input type="text" id="addrProvince" class="form-control mb-2" placeholder="Province" required>
                        </div>
                        <div class="col-12">
                            <input type="text" id="addrCity" class="form-control mb-2" placeholder="City / Municipality" required>
                        </div>
                        <div class="col-12">
                            <input type="text" id="addrBarangay" class="form-control mb-2" placeholder="Barangay" required>
                        </div>
                        <div class="col-12">
                            <input type="text" id="addrPostal" class="form-control mb-2" placeholder="Postal Code" required>
                        </div>
                        <div class="col-12">
                            <input type="text" id="addrStreet" class="form-control mb-2" placeholder="Street Name" required>
                        </div>
                        <div class="col-12">
                            <input type="text" id="addrHouse" class="form-control" placeholder="House Number" required>
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
                            const fullAddr = parts.join(', ');
                            document.getElementById('fullAddress').value = fullAddr;
                        };

                        ['addrRegion', 'addrProvince', 'addrCity', 'addrBarangay', 'addrPostal', 'addrStreet', 'addrHouse'].forEach(id => {
                            const el = document.getElementById(id);
                            if(el) el.addEventListener('input', updateAddress);
                        });
                    });
                </script>

                <div class="mb-3">
                    <label for="verification_document" class="form-label">Verification Document (ID/Certificate)</label>
                    <input type="file" id="verification_document" name="verification_document" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn-submit">Verify</button>
            </form>

            <div class="auth-link">
                Already have an account? <a href="{{ route('beautician.login') }}">Login</a>
            </div>
        </div>
    </div>

</body>
</html>