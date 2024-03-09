<template>
    <main>
        <!-- Navigation Bar-->
        <header id="topnav">
            <!-- Topbar Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- LOGO -->
                            <div class="logo-box">
                                <a href="#" class="logo text-center">
                                    <span class="logo-lg">
                                        <img src="/images/logo.png" alt="" height="50">
                                        <!-- <span class="logo-lg-text-light">UBold</span> -->
                                    </span>
                                    <span class="logo-sm">
                                        <!-- <span class="logo-sm-text-dark">U</span> -->
                                        <img src="/images/logo-sm.png" alt="" height="24">
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9 text-right">
                            <h2 class="title-app mt-3">APLIKASI ANJUNGAN PENDAFTARAN MANDIRI</h2>
                        </div>
                    </div>
                </div> <!-- end container-fluid-->
            </div>
            <!-- end Topbar -->
            <!-- end navbar-custom -->

        </header>
        <!-- End Navigation Bar-->
        <div class="wrapper" style="padding-top: 100px !important;">
            <router-view></router-view>
            <!-- <div class="" id="app">
            </div> -->
        </div>
        <!-- <div class="container mt-5">
            <router-view></router-view>
        </div> -->
    </main>
</template>

<script>
export default {}
</script>