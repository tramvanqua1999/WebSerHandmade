<template>
	<div class="wrapper" >
		<appheader v-if="this.$store.state.Auth.isLoggedIn" ></appheader>
		<appaside v-if="this.$store.state.Auth.isLoggedIn &&  (this.type == 2)"  ></appaside>
		<appaside-shop v-if="this.$store.state.Auth.isLoggedIn &&  (this.type == 1)"  ></appaside-shop>
        <router-view></router-view>
		<app-footer v-if="this.$store.state.Auth.isLoggedIn"></app-footer>
	</div>
</template>

<script>
	import AppHeader from "./components/header/Header";
	import AppAside from "./components/aside/Aside";
	import AppFooter from "./components/footer/Footer";
	// ------------------Shop-----------------
	import AppAsideShop from "./components/aside/AsideShop";

	export default {
		name: "App",
		data(){
			return{
				token: '',
				isLoggedIn : false,
				type : ''
			}
		},
		components: {
			'appheader' : AppHeader,
			'appaside': AppAside,
			'appaside-shop':AppAsideShop,
			AppFooter,
			
			
		},
		created() {
			this.interval = setInterval(this.getType, 500);
			this.type = localStorage.getItem('type');
			console.log(this.type);
		},
		 methods: {
			 getType(){
				this.type = localStorage.getItem('type');
			 }
		 }
	}
</script>