<script lang="ts" setup>
import { Link, usePage } from "@inertiajs/vue3";
import { onMounted, onUnmounted } from "vue";
import { USER_ROLE } from "@/Components/utils/types";
// Definimos una función para cargar el script
// const loadMainScript = () => {
//   const script = document.createElement("script");
//   script.src = "/assets/js/main.js"; // Asegúrate de poner la ruta correcta a tu script
//   script.async = true;
//   script.onload = () => {
//     console.log("El script main.js se ha cargado correctamente.");
//   };
//   document.body.appendChild(script); // Añadimos el script al final del cuerpo del documento
// };

// onMounted(() => {
//   loadMainScript();
// });

// onUnmounted(() => {
//   const scriptElement = document.querySelector(
//     'script[src="/assets/js/main.js"]'
//   );
//   if (scriptElement) {
//     scriptElement.remove();
//     console.log("El script main.js se ha eliminado correctamente.");
//   }
// });
</script>

<template>
  <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a :href="route('dashboard')" class="app-brand-link">
        <span class="app-brand-logo demo">
          <svg
            width="25"
            viewBox="0 0 25 42"
            version="1.1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
          >
            <defs>
              <path
                d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                id="path-1"
              ></path>
              <path
                d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                id="path-3"
              ></path>
              <path
                d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                id="path-4"
              ></path>
              <path
                d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                id="path-5"
              ></path>
            </defs>
            <g
              id="g-app-brand"
              stroke="none"
              stroke-width="1"
              fill="none"
              fill-rule="evenodd"
            >
              <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                <g id="Icon" transform="translate(27.000000, 15.000000)">
                  <g id="Mask" transform="translate(0.000000, 8.000000)">
                    <mask id="mask-2" fill="white">
                      <use xlink:href="#path-1"></use>
                    </mask>
                    <use fill="#696cff" xlink:href="#path-1"></use>
                    <g id="Path-3" mask="url(#mask-2)">
                      <use fill="#696cff" xlink:href="#path-3"></use>
                      <use
                        fill-opacity="0.2"
                        fill="#FFFFFF"
                        xlink:href="#path-3"
                      ></use>
                    </g>
                    <g id="Path-4" mask="url(#mask-2)">
                      <use fill="#696cff" xlink:href="#path-4"></use>
                      <use
                        fill-opacity="0.2"
                        fill="#FFFFFF"
                        xlink:href="#path-4"
                      ></use>
                    </g>
                  </g>
                  <g
                    id="Triangle"
                    transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) "
                  >
                    <use fill="#696cff" xlink:href="#path-5"></use>
                    <use
                      fill-opacity="0.2"
                      fill="#FFFFFF"
                      xlink:href="#path-5"
                    ></use>
                  </g>
                </g>
              </g>
            </g>
          </svg>
        </span>
        <span class="app-brand-text demo menu-text fw-bolder ms-2">Sneat</span>
      </a>

      <a
        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none"
      >
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      <!-- Dashboard -->
      <li
        class="menu-item"
        :class="{ active: $page.component === 'Dashboard' }"
      >
        <Link :href="route('dashboard')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Categoy">Dashboard</div>
        </Link>
      </li>

      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Personal</span>
      </li>
      <li
        v-if="$page.props.auth.user.role_id === USER_ROLE.ADMIN.role"
        class="menu-item"
        :class="{ active: $page.component === 'views/EnterpriseView' }"
      >
        <Link :href="route('enterprise.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Categoy">Empresa</div>
        </Link>
      </li>
      <li
        v-if="$page.props.auth.user.role_id === USER_ROLE.ADMIN.role"
        class="menu-item"
        :class="{ active: $page.component === 'views/UserView' }"
      >
        <Link :href="route('user.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Daily Cash">Usuarios</div>
        </Link>
      </li>

      <li
        class="menu-item"
        :class="{ active: $page.component === 'views/ClientView' }"
      >
        <Link :href="route('client.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Daily Cash">Clientes</div>
        </Link>
      </li>

      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Almacen</span>
      </li>

      <li
        v-if="$page.props.auth.user.role_id === USER_ROLE.ADMIN.role"
        class="menu-item"
        :class="{ active: $page.component === 'views/CategoryView' }"
      >
        <Link :href="route('category.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Categoy">Categorías</div>
        </Link>
      </li>
      <li
        v-if="$page.props.auth.user.role_id === USER_ROLE.ADMIN.role"
        class="menu-item"
        :class="{ active: $page.component === 'views/SubCategoryView' }"
      >
        <Link :href="route('subCategory.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="SubCategory">Sub Categorías</div>
        </Link>
      </li>
      <li
        class="menu-item"
        :class="{ active: $page.component === 'views/ProductView' }"
      >
        <Link :href="route('product.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Product">Productos</div>
        </Link>
      </li>
      <li
        class="menu-item"
        :class="{ active: $page.component === 'views/ProviderView' }"
      >
        <Link :href="route('provider.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Product">Proveedores</div>
        </Link>
      </li>
      <li
        class="menu-item"
        :class="{ active: $page.component === 'views/ShopView' }"
      >
        <Link :href="route('shop.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Shop">Ventas</div>
        </Link>
      </li>

      <!-- Layouts -->
      <li class="menu-item">
        <a class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-layout"></i>
          <div data-i18n="Layouts">Layouts</div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="layouts-without-menu.html" class="menu-link">
              <div data-i18n="Without menu">Without menu</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-without-navbar.html" class="menu-link">
              <div data-i18n="Without navbar">Without navbar</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-container.html" class="menu-link">
              <div data-i18n="Container">Container</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-fluid.html" class="menu-link">
              <div data-i18n="Fluid">Fluid</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-blank.html" class="menu-link">
              <div data-i18n="Blank">Blank</div>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Ventas</span>
      </li>

      <li
        class="menu-item"
        :class="{ active: $page.component === 'views/DailyCashView' }"
      >
        <Link :href="route('dailyCash.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Daily Cash">Caja Diaria</div>
        </Link>
      </li>
      <li
        class="menu-item"
        :class="{ active: $page.component === 'views/SaleView' }"
      >
        <Link :href="route('sale.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Daily Cash">Historial De Ventas</div>
        </Link>
      </li>
      <li
        class="menu-item"
        :class="{
          active: $page.component === 'views/SaleCredit/CreditSaleView',
        }"
      >
        <Link :href="route('saleCredit.index')" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Daily Cash">Ventas a Crédito</div>
        </Link>
      </li>
      <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Pages</span>
      </li>
      <li class="menu-item">
        <a class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-dock-top"></i>
          <div data-i18n="Account Settings">Account Settings</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="pages-account-settings-account.html" class="menu-link">
              <div data-i18n="Account">Account</div>
            </a>
          </li>
          <li class="menu-item">
            <a
              href="pages-account-settings-notifications.html"
              class="menu-link"
            >
              <div data-i18n="Notifications">Notifications</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="pages-account-settings-connections.html" class="menu-link">
              <div data-i18n="Connections">Connections</div>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </aside>
</template>

<style scoped></style>
