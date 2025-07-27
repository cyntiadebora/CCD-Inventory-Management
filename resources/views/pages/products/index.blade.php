@extends('layouts.main')


@section('content')
    <div class="container-fluid py-4">
      <div class="row">
  <div class="col-lg-3 col-md-6 col-12 mb-4">
    <!-- Card 1: Users Active -->
    <div class="card">
      <span class="mask bg-primary opacity-10 border-radius-lg"></span>
      <div class="card-body p-3 position-relative">
        <div class="row">
          <div class="col-8 text-start">
            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
              <i class="fas fa-users text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
            </div>
            <h5 class="text-white font-weight-bolder mb-0 mt-3">1600</h5>
            <span class="text-white text-sm">Users Active</span>
          </div>
          <div class="col-4">
            <div class="dropdown text-end mb-6">
              <a href="javascript:;" class="cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-h text-white"></i>
              </a>
              <ul class="dropdown-menu px-2 py-3">
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-12 mb-4">
    <!-- Card 2: All Request -->
    <div class="card">
      <span class="mask bg-warning opacity-10 border-radius-lg"></span>
      <div class="card-body p-3 position-relative">
        <div class="row">
          <div class="col-8 text-start">
            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
              <i class="fas fa-inbox text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
            </div>
            <h5 class="text-white font-weight-bolder mb-0 mt-3">10</h5>
            <span class="text-white text-sm">All Request</span>
          </div>
          <div class="col-4">
            <div class="dropstart text-end mb-6">
              <a href="javascript:;" class="cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-h text-white"></i>
              </a>
              <ul class="dropdown-menu px-2 py-3">
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-12 mb-4">
    <!-- Card 3: Balance Stock -->
    <div class="card">
      <span class="mask bg-success opacity-10 border-radius-lg"></span>
      <div class="card-body p-3 position-relative">
        <div class="row">
          <div class="col-8 text-start">
            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
              <i class="fas fa-box text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
            </div>
            <h5 class="text-white font-weight-bolder mb-0 mt-3">2300</h5>
            <span class="text-white text-sm">Balance Stock</span>
          </div>
          <div class="col-4">
            <div class="dropdown text-end mb-6">
              <a href="javascript:;" class="cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-h text-white"></i>
              </a>
              <ul class="dropdown-menu px-2 py-3">
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-12 mb-4">
    <!-- Card 4: Re-Order -->
    <div class="card">
      <span class="mask bg-info opacity-10 border-radius-lg"></span>
      <div class="card-body p-3 position-relative">
        <div class="row">
          <div class="col-8 text-start">
            <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
              <i class="fas fa-redo text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
            </div>
            <h5 class="text-white font-weight-bolder mb-0 mt-3">940</h5>
            <span class="text-white text-sm">Re-Order</span>
          </div>
          <div class="col-4">
            <div class="dropstart text-end mb-6">
              <a href="javascript:;" class="cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-h text-white"></i>
              </a>
              <ul class="dropdown-menu px-2 py-3">
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!--TABLE STOCK DI DASHBOARD-->
  <div class="row my-4">
        <div class="col-12 mb-4">

          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Item Stock Status</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
               <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
    <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Items</th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Min</th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Max</th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Balance Stock</th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/CompressionTop.jpg" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Compression Top</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
 
  <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="./images/FemaleRedBlazzer.jpg" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Female Red Blazzer</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>

   <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/FemaleRedSkirt.jpg" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Female Red Skirt</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
                
   <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="images/MaleBlackBlazer.jpg" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Male Black Blazer</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
    <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/MaleBlackPants.jpg" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Male Black Pants</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
  <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/MaleShoes.png" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Male Shoes</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
    <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/FemaleCabinShoes.png" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Female Cabin Shoes</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
  <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/FemaleGroundShoes.png" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Female Ground Shoes</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
  <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/Wing.jpg" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Wing</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
  <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/FemaleHandBag.png" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Female Hand Bag</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
  <tr>
    <td class="text-start">
      <div class="d-flex justify-content-start align-items-center px-2 py-1">
        <img src="/images/TrolleyBag.jpg" class="avatar avatar-sm me-3" alt="user1">
        <h6 class="mb-0 text-sm">Trolley Bag</h6>
      </div>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">10 items</p>
    </td>
    <td class="text-center">
      <p class="text-xs font-weight-bold mb-0">500 items</p>
    </td>
    <td class="text-center">
      <span class="text-secondary text-xs font-weight-bold">200</span>
    </td>
    <td class="text-center">
      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
        Edit
      </a>
    </td>
  </tr>
                        
                    
                
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>

@endsection