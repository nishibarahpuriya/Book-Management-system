<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand" href="javascript:void(0)">
        <img src="/images/img/logo.png" alt="" width="50" height="44" class="d-inline-block align-text-top">
            Books Management System
        </a>
     </div>
        <div class="collapse navbar-collapse">
            <!-- for logged-in user-->
            <div class="navbar-nav" v-if="isLoggedIn">
                <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                <router-link to="/books" class="nav-item nav-link">Books</router-link>
                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
            </div>
            <!-- for non-logged user-->
            <div class="navbar-nav" v-else>
                <router-link to="/" class="nav-item nav-link">Home</router-link>
                <router-link to="/login" class="nav-item nav-link">login</router-link>
                <router-link to="/register" class="nav-item nav-link">Register
                </router-link>
                <router-link to="/about" class="nav-item nav-link">About</router-link>
            </div>
        </div>
    </nav>
    <div class="container">
    <router-view/>
    </div>
</template>

<script>
export default {
    name: "App",
    data() {
        return {
            isLoggedIn: false,
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true
        }
    },
    methods: {
        logout(e) {
            console.log('ss')
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>