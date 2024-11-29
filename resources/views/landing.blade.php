<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
body{
    font-family: 'Averia Serif Libre';
}
.circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
}

.circle-content {
    text-align: center;
}

#msg textarea::placeholder{
    padding-left: 12px;
    padding-top: 2px;
}
.circle.blue {
    background-color: #00328E;
    color: white;
}

.circle.white {
    background-color: white;
    color: #00328E;
    border: 2px solid #00328E;
}

.circle h5 {
    margin-bottom: 5px;
    font-size: 1.2rem;
}

.circle p {
    margin: 0;
    font-size: 1.8rem;
    font-weight: bold;
}

.navbar li a:hover{
    border-bottom: 1.5px solid #00328E;
}

.navbar li a {
    transition: all;
    transition-duration: 300ms;
}

footer {
    font-family: Arial, sans-serif;
}

footer h5 {
    font-size: 1.1rem;
    font-weight: 600;
}

footer .social-links a {
    font-size: 1.2rem;
    transition: color 0.3s ease;
}

footer .social-links a:hover {
    color:  aqua !important;
}

.navbar {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            right: 0;
            background-color: white;
            border-bottom: 1px solid;
        }


          .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar{
            border-bottom: 1px solid;
        }
        .navbar-brand img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .navbar-brand h6 {
            margin-bottom: 0;
            color: #00328E;
            font-size: 1rem;
        }

        #announcementCarousel {
            padding-top: 70px; /* Sesuaikan dengan tinggi navbar */
        }
       
        .carousel-item img {
            object-fit: cover;
            height: 500px;
            width: 100%;
        }
        .layanan-section, .location-section, .contact-section, .statistik-section {
            padding: 80px 0;
            min-height: 90vh;
        }

        .layanan-section, .location-section {
            background-color: #00328E;
            color: white;
        }
       .statistik-section .card, .location-section .card{
            height: 100%;
            margin-bottom: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .card {
            background-color: white;
            color: black;
        }
        .contact-section {
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        .contact-section .icon {
            font-size: 2rem;
            color: #00328E;
        }
        
        @media (max-width: 768px) {
            .carousel-item img {
                height: 300px;
            }
            .layanan-section, .statistik-section, .location-section, .contact-section {
                min-height: auto;
            }
            .social-links {
        justify-content: center !important;
        margin-top: 1rem;
    }

            #announcementCarousel {
                padding-top: 70px; /* Sesuaikan untuk mobile */
            }

            .navbar-brand h6 {
                font-size: 0.9rem;
            }
            .circular-card {
        max-width: 200px; /* Smaller circles on mobile devices */
    }
    .card-title {
        font-size: 1rem;
    }
    .card-text {
        font-size: 1.5rem !important;
    }
             
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logolp31.png') }}" alt="Logo" style="width: 27%; height: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#layanan" style="color: #00328E;">Layanan</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#statistik" style="color: #00328E;">Statistik</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#lokasi" style="color: #00328E;">Lokasi</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#kontak" style="color: #00328E;">Kontak</a>
                    </li>
                   
                </ul>
                
                <a class="btn text-white ms-3 mt-2 mt-lg-0" href="{{ route('login') }}" style="background-color: #00328E;">Login</a>
            </div>
        </div>
    </nav>

    <!-- Carousel untuk Pengumuman -->
    <div id="announcementCarousel" class="carousel slide mt-0" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($carousels as $index => $carousel)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $carousel->image) }}" class="d-block w-100" alt="Slide {{ $index + 1 }}">
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#announcementCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#announcementCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Section Layanan -->
    <section id="layanan" class="layanan-section">
        <div class="container">
            <h2>Layanan</h2>
            <div class="justify-content-center">
                <div class="mb-4">
                    <div class="card text-center w-70 mt-5" data-aos="zoom-in-up" style="height: 350px;">
                        <div class="card-body">
                            <i class="fas fa-book icon" style="font-size: 3rem; margin-top: 20px; color:#00328E;"></i> 
                            <h5 class="card-title mt-4 fs-4">Peminjaman Buku</h5>
                            <p class="card-text" style="font-size: 25px; margin-top: 50px; width: 500px; margin-left: 27%;">Kami menyediakan layanan peminjaman buku untuk semua mahasiswa dan dosen.</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Section Statistik -->
  <!-- Section Statistik -->
<!-- Section Statistik -->
<section id="statistik" class="statistik-section">
    <div class="container">
        <h2 class="text-center mb-5">Statistik Perpustakaan</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4" data-aos="fade-right">
                <div class="circle blue mt-5">
                    <div class="circle-content">
                        <h5>Buku</h5>
                        <p>{{ $bookCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="circle white mt-5">
                    <div class="circle-content">
                        <h5>Buku Di Pinjam</h5>
                        <p>5.000</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-left">
                <div class="circle blue mt-5">
                    <div class="circle-content">
                        <h5>Pengguna Aktif</h5>
                        <p>1.000</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Section Lokasi -->
    <section id="lokasi" class="location-section">
        <div class="container">
            <h2 class="text-center mb-5">Lokasi & Jam Operasional</h2>
            <div class="row justify-content-center">
                <div class="col-md-6 mb-4">
                    <div class="card text-center h-100" data-aos="flip-left">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Lokasi Kami</h5>
                            <p class="card-text">Jl. Raya Bogor KM38 No.56, Sukamaju, Kec. Cilodong, Kota Depok, Jawa Barat 16415, Indonesia</p>
                            <!-- Embed Google Map -->
                            <div class="ratio ratio-16x9">
                                <iframe 
                                    src="https://maps.google.com/maps?q=-6.430095350747043, 106.855464435102756&z=15&output=embed" 
                                    width="100%" 
                                    height="200" 
                                    frameborder="0" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    aria-hidden="false" 
                                    tabindex="0">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card text-center h-100" data-aos="flip-right">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Jam Operasional</h5>
                            <p class="card-text fs-5" style="margin-top: 100px;">
                                Senin - Jumat: <br> 08.00 - 17:00
                            </p>
                            <p class="card-text fs-5 mt-4">
                                Sabtu : <br> 08:00 - 17:00
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="contact-section">
        <div class="container">
            <h2>Kontak Kami</h2>
            <div class="row justify-content-center">
                <div class="col mb-4">
                    <div class="card text-center mt-5"  style="background-color: #00328E; height: 392px;">
                    <div class="card-body">
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            <p class="fs-4 text-white mt-3">Send Message</p>
                            <div class="form-group mt-5">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Your Name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="form-group mt-4">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       placeholder="Email address" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="form-group mt-4">
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                          placeholder="Your Message">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                        
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                        
                            <button type="submit" class="btn btn-light mt-4 d-flex justify-content-start">Send Message</button>
                        </form>
                    </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 ">
                    <div class="card text-center mt-5">
                    <div class="card-body"> 
                    <i class="fas fa-phone icon mt-5"></i>
                    <p class="mt-3">Telepon: +62 812 3456 7890</p>
                    </div>
                    </div>
                    <div class="card text-center mt-5" style="background-color: #00328E;">
                        <div class="card-body">
                            <i class="fas fa-envelope icon mt-5" style="color: white;"></i>
                            <p class="mt-3 text-white">Email: perpustakaan@domain.com</p>
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    </section>

    <!-- Footer -->
   <!-- Footer -->
<footer class="text-white py-5" style="background-color: #00328E;">
    <div class="container">
        <div class="row">
            <!-- Logo and Description -->
            <div class="col-md-3 mb-4">
               <img src="{{ asset("images/logoputih.png") }}" style="width: 240px;" alt="">
               <div class="social-links d-flex gap-3 justify-content-start mt-4">
                <a href="https://www.facebook.com/lp3i.depok" class="text-white"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.tiktok.com/@lp3i.depok?_t=8rgQ3EVAEoU&_r=1" class="text-white"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.instagram.com/lp3i.depok?igsh=OWF0ZGJnZDRpZGR3" class="text-white"><i class="fab fa-instagram"></i></a>
                <a href="https://www.youtube.com/@pljdepok" class="text-white"><i class="fab fa-youtube"></i></a>
            </div>
            </div>

            <!-- Navigation -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Navigation</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#layanan" class="text-white text-decoration-none">Layanan</a></li>
                    <li class="mb-2"><a href="#statistik" class="text-white text-decoration-none">Statistik</a></li>
                    <li class="mb-2"><a href="#lokasi" class="text-white text-decoration-none">Lokasi</a></li>
                    <li class="mb-2"><a href="#kontak" class="text-white text-decoration-none">Kontak</a></li>
                </ul>
            </div>

            <!-- Information -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Information</h5>
                <ul class="list-unstyled">
                    <li class="mb-2 text-white">+123456789</li>
                    <li class="mb-2"><a href="mailto:mudassar@gmail.com" class="text-white text-decoration-none">mudassar@gmail.com</a></li>
                    <li class="mb-2 text-white">Jl. Raya Bogor KM38 No.56, Sukamaju, Kec. Cilodong, Kota Depok, Jawa Barat 16415, Indonesia</li>
                </ul>
            </div>

            <!-- Opening Hours -->
            <div class="col-md-3 mb-4">
                <h5 class="mb-3">Open Library</h5>
                <ul class="list-unstyled">
                    <li class="mb-2 text-white">Senin - Jumat: 8:00 - 17:00</li>
                    <li class="mb-2 text-white">Sabtu: 8:00 - 17:00</li>
                </ul>
            </div>
        </div>

        <!-- Copyright and Social Media -->
       
    </div>
</footer>



    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1200,
        });
    </script>
</body>
</html>