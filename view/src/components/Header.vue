<template>
  <nav id="header" class="d-flex justify-content-between border-bottom border-info">

    <router-link to="/" class="thumbnail my-auto text-center">
      <img
        src="/neika.png"
        class="img-fluid h-100"
        alt="Neika-image"
      />
    </router-link>

    <div class="d-flex flex-fill justify-content-around align-content-stretch">

      <div class="my-auto fs-3">
        <router-link to="/" class="nav-link">
          <div class="d-flex flex-column text-center">
            <span class="fas fa-torii-gate me-1"></span>
            Accueil
          </div>
        </router-link>
      </div>

      <div class="my-auto fs-3">
        <router-link to="/images" class="nav-link">
          <div class="d-flex flex-column text-center">
            <span class="far fa-images"></span>
            Images
          </div>
        </router-link>
      </div>

      <div class="my-auto fs-3">
        <router-link to="/profile" class="nav-link">
          <div class="d-flex flex-column text-center">
            <span class="fas fa-tags"></span>
            Tags
          </div>
        </router-link>
      </div>

      <div class="my-auto fs-3">
        <router-link to="/upload" class="nav-link">
          <div class="d-flex flex-column text-center">
            <span class="fas fa-upload"></span>
            Upload
          </div>
        </router-link>
      </div>
    </div>

    <router-link to="/profile">
      <UserThumbnail/>
    </router-link>

  </nav>
</template>

<script lang="js">
import {computed, defineComponent} from 'vue';
import {useStore as useUserStore} from './../store/modules/user';
import UserThumbnail from './user/UserThumbnail.vue';
import Button from 'primevue/button';
import Card from 'primevue/card';

export default defineComponent({
    name: 'Header',
    components: {
        Button,
        Card,
        UserThumbnail,
    },
    setup() {
        const store = useUserStore();
        const isLoggedIn = computed(() => store.getters.isLogged);
        return {isLoggedIn};
    },
});
</script>

<style scoped>

#header {
    height: 110px;
}

nav {
    max-height: 210px !important;
}

.nav-link {
    padding-top: 0;
    padding-bottom: 0;
    transition: 0.5s;
}
.nav-link:hover{
    color: crimson;
}

.thumbnail {
    height: 90%;
    width: 210px;
}

.section-separator {
    border-bottom: 4px double darkgrey;
    margin-top: 3px;
    margin-bottom: 3px;
}
</style>
