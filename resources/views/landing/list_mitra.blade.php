@extends('layouts.landing')
@section('title', __('Mitra ' . $province->nama))

@section('style')
    <style>
        @font-face {
            font-family: "gotham-black";
            src: url({{ asset('assets/images/font/Gotham-Black-Regular.ttf') }} );
        }

        @font-face {
            font-family: "lato";
            src: url({{ asset('assets/images/font/Lato-Regular.ttf') }});
        }

        .mybutton {
            background-color: #efc368;
            display: block;
            width: 200px;
            padding: 8px;
            border-radius: 20px;
            color: #214842;
            margin: auto;
            margin-top: 16px;
        }

        .myInput {
            border: 1px solid grey;
            padding: 4px;
            border-radius: 10px;
            margin-top: 16px;
            background-color: white;
        }

        .mitra_list {
            background-color: white;
            text-align: center;
            padding: 20px;
            min-height: 100%;
            margin-block: 20px;
        }

        .mitra_list h4 {
            font-family: "gotham-black";
            font-size: 20px;
            color: black;
        }

        .mitra_list p {
            font-family: "lato";
        }

        @media (max-width: 768px) {
            .splide__slide {
                width: 100%;
            }
        }

        .modal {
            display: none;
            /* sembunyikan modal secara default */
            position: fixed;
            /* posisi tetap */
            z-index: 1;
            /* taruh modal di atas konten lain */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            /* tambahkan scroll jika konten terlalu panjang */
            background-color: rgba(0, 0, 0, 0.4);
            /* warna latar belakang transparan */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* jarak atas dan bawah dari modal */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* lebar modal */
            position: relative;
            animation-name: modalopen;
            animation-duration: 0.5s;
        }

        @keyframes modalopen {
            from {
                top: -300px;
                opacity: 0;
            }

            to {
                top: 0;
                opacity: 1;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .accordion {
            border: 1px solid #ddd;
        }

        .accordion-header {
            background-color: #eee;
            padding: 10px;
            margin: 0;
            cursor: pointer;
            text-align: center;
        }

        .accordion-content {
            padding: 10px;
            display: none;
        }

        .list_city {
            display: flex;
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
        }

        .list_city>button {
            font-size: 16px;
            padding: 8px;
            color: white;
            margin: 10px;
            border-radius: 100px;
            color: mediumseagreen;
            background-color: white;
            border: 1px solid mediumseagreen;
        }

        .list_city>button:hover {
            color: white;
            background-color: mediumseagreen;
        }

        .list_city>.selected {
            background-color: mediumseagreen;
            color: white;
        }

        .search-box {
            display: none;
            align-items: center;
            width: 300px;
            border: 2px solid mediumseagreen;
            border-radius: 5px;
            overflow: hidden;
            justify-content: center;
            margin: auto;
            margin-block: 20px;
        }

        #apotek {
            flex: 1;
            padding: 10px;
            border: none;
            background-color: white;
        }

        #search {
            background-color: mediumseagreen;
            color: white;
            border: none;
            padding: 10px 15px;
        }

        .splide__pagination {
            bottom: -1.5em;
        }

        .backButton {
            font-size: 16px;
            padding: 8px;
            color: white;
            margin: 10px;
            color: mediumseagreen;
            border-color: mediumseagreen;
        }

        .backButton:hover {
            color: white;
            background-color: mediumseagreen;
        }
    </style>
@endsection

@section('content')
    <section class="myproducts" style="margin-bottom: 40px;">
        <div class="featured" style="padding-top: 20px">
            <div class="container">
                <div class="featured_header">
                    <h2 class="featured_header-title">
                        <?= isset($province) ? 'Mitra Area ' . $province->nama : 'Semua Mitra' ?></h2>
                </div>
                <input type="hidden" id="prov" value="<?= isset($province->id) ? $province->id : '' ?>">
                <div class="list_city">
                    @foreach ($cities as $city)
                        <button class="thisbutton" data-id="<?= $city->id ?>"><img
                                src="{{ asset('assets/images/logo/home.svg') }}"
                                style="display: inline-block; width: 20px; vertical-align: bottom;">
                            <span><?= $city->nama ?></span></button>
                    @endforeach
                </div>
                <h4 style="text-align: center; padding-top: 20px" id="title"></h4>
                <form class="search-box">
                        <input type="text" id="apotek" placeholder="Cari Apotek...">
                        <button type="submit" id="search"><i class="fa fa-search"></i></button>
                </form>
                <section>
                    <div id="not_found" class="my-4"></div>
                </section>
                <section id="list-carousel" class="splide my-4" aria-label="My Awesome Gallery">
                    <div class="splide__track" id="list_mitra">
                        <ul class="splide__list">

                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        var prov = $("#prov").val();
        var city, apotek;
        var testimoni = new Splide("#list-carousel", {
            grid: {
                rows: 2,
                cols: 3,
                gap: {
                    row: "1rem",
                    col: "1.5rem",
                },
            },
            breakpoints: {
                767: {
                    grid: {
                        rows: 8,
                        cols: 1,
                        gap: {
                            row: "2rem",
                            col: "1.5rem",
                        },
                    },
                },
            },
        });
        testimoni.mount(window.splide.Extensions);
        testimoni.destroy();

        // list_mitra(prov, city, apotek);

        $(".thisbutton").on("click", function(e) {
            e.preventDefault();
            apotek = "";
            $("#apotek").val("");
            if ($(this).hasClass("selected")) {
                city = "";
                $("#title").html("");
                $("#list_mitra").html("");
                testimoni.destroy();
                $(".thisbutton").removeClass("selected");
                $(".search-box").css("display", "none");
            } else {
                city = $(this).data("id");
                list_mitra(prov, city, apotek);
                $(".thisbutton").removeClass("selected");
                $(`[data-id=${city}]`).addClass("selected");
                $(".search-box").css("display", "flex");
                $(".list_city").css("display", "none");
            }
        });

        $("#search").click(function(e) {
            e.preventDefault();
            apotek = $("#apotek").val();
            list_mitra(prov, city, apotek);
        });

        function list_mitra(prov, city, apotek) {
            Swal.showLoading();
            $.ajax({
                url: `{{ route('list-apotek') }}`,
                type: "POST",
                dataType: "JSON",
                data: {
                    prov: prov,
                    city: city,
                    apotek: apotek,
                    _token: `{{ csrf_token() }}`
                },
                success: function(data) {
                    if (data.status) {
                        $("#not_found").html("");
                        $("#list_mitra").html(data.html_mitra);
                        testimoni.destroy();
                        testimoni.mount(window.splide.Extensions);
                        Swal.fire({
                            icon: "success",
                            title: "Sukses Load data",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#title").html(
                            `<button class="backButton"><i class="fa fa-arrow-left"></i></button>${data.city}`
                        );
                    } else {
                        $("#not_found").html(data.html_mitra);
                        $("#list_mitra").html("");
                        testimoni.destroy();
                        Swal.fire({
                            icon: "error",
                            title: "Data Not Found",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#title").html("");
                    }
                    $("#myModal").css("display", "none");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    notif_error(textStatus);
                },
            });
        }

        $(document).on("click", ".backButton", function(e) {
            e.preventDefault();
            $("#title").html("");
            $("#list_mitra").html("");
            testimoni.destroy();
            $(".thisbutton").removeClass("selected");
            $(".search-box").css("display", "none");
            $(".list_city").css("display", "flex");
        });

        // ketika tombol di klik, tampilkan modal
        $("#myBtn").on("click", function() {
            $("#myModal").css("display", "block");
        });

        // ketika pengguna mengklik span (x), tutup modal
        $(".close").on("click", function() {
            $("#myModal").css("display", "none");
        });

        // ketika pengguna mengklik di luar modal, tutup modal
        $(window).on("click", function(event) {
            if (event.target == $("#myModal")[0]) {
                $("#myModal").css("display", "none");
            }
        });

        $(".accordion-header").on("click", function() {
            $(this).toggleClass("active");
            $(this).next(".accordion-content").slideToggle();
            $(".accordion-header").not(this).removeClass("active");
            $(".accordion-content").not($(this).next(".accordion-content")).slideUp();
        });
    </script>
@endsection
