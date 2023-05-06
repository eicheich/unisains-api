   <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
       <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">UNISAINS</a>
       <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
           data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search"
           aria-label="Search">
       <div class="navbar-nav">
           <div class="nav-item text-nowrap">
              <form action="{{route('logout.post')}}" method="post">
                @csrf
                 <button class="nav-link px-3 btn-logout" type="submit">Sign out</button>
                 <style>
                    .btn-logout{
                        background-color: transparent;
                        border: none;
                        color: white;
                    }
                 </style>
              </form>
           </div>
       </div>
   </header>
   <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-body-tertiary sidebar collapse">
       <div class="position-sticky pt-3 sidebar-sticky">
           <ul class="nav flex-column">
               <li class="nav-item">
                   <a class="nav-link active" aria-current="page" href="#">
                       <span data-feather="home" class="align-text-bottom"></span>
                       Dashboard
                   </a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" href="#">
                       <span data-feather="file" class="align-text-bottom"></span>
                       Kursus
                   </a>
               </li>
           </ul>
           <h6
               class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
               <span>Area Pengajar</span>
               <a class="link-secondary" href="#" aria-label="Add a new report">
                   <span data-feather="plus-circle" class="align-text-bottom"></span>
               </a>
           </h6>
           <ul class="nav flex-column mb-2">
               <li class="nav-item">
                   <a class="nav-link" href="#">
                       <span data-feather="file-text" class="align-text-bottom"></span>
                       Komunitas
                   </a>
               </li>
           </ul>
       </div>
   </nav>
