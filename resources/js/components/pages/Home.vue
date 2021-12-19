<template>
    <div class="content-wrapper">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Tài khoản </h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <router-link to="/home">Home</router-link>
                                </li>
                                <li class="breadcrumb-item active">Tài khoản</li>
                            </ol>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </section>
            <!-- /.container-fluid -->
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="white-box">
                                    <router-link
                                        :to="{ path: '/create' }"
                                        style="width:80px"
                                        class="btn btn-success waves-effect waves-light m-r-10"
                                        ><i class="nav-icon fas fa-plus"></i>
                                    </router-link>
                                    <br /> <br />
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            Dữ liệu danh sách tài khoản
                                                        </h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div class="form-group has-search">
                                                            <span class="fa fa-search form-control-feedback"></span>
                                                            <input type="text" class="form-control" v-model="searchQuery" placeholder="Search">
                                                        </div>
                                                        <div>
                                                            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                                        <thead>
                                                            <tr role="row"><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">id</th>
                                                            <th class="sorting_desc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" aria-sort="descending">Tài khoản</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Loại(s)</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Xóa</th>
                                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Thông tin</th></tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="data in  resultQuery" role="row" class="odd" :key="data.id">
                                                        <td>{{data.id}}</td>
                                                        <td class="sorting_1">{{data.username}}</td>
                                                        <td>{{getText(data.type)}}</td>
                                                        <td class=""><button class="btn btn-danger" @click.prevent="deletePost(data.id)"><i class="fas fa-trash-alt"></i></button></td>
                                                        <td v-if="data.type != 2" class="sorting_1"><button class="btn btn-success" @click.prevent="detailPost(data.id, data.type)"><i class="fas fa-eye"></i></button></td>
                                                        <td v-else class="sorting_1"><button class="btn btn-default">You don't permission</button></td>
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
                </div>
            </div>
        </section>
    </div>
</template>
<style scoped>
    .has-search .form-control {
        padding-left: 2.375rem;
        box-shadow: 0 0 10px #719ECE;
    }
  
    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
}
</style>
<script>

// add to component

export default {
    name: "Home",
    data() {
        return {
            searchQuery: null,
            datas: []
        };
    },
    computed: {
            resultQuery(){
            if(this.searchQuery){
                 if(this.filterUsersByType(this.datas).length !=0){
                     return this.filterUsersByType(this.datas)
                 }else {
                     return this.filterUsersByName(this.datas)
                 }
               
            }else{
                return this.datas;
            }
            }
        },
    created() {
        this.interval = setInterval(this.refreshData, 1000);
        console.log(localStorage.getItem('type'));
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    methods: {
        refreshData() {
            let uri = "http://127.0.0.1:8000/api/home";
            this.axios.get(uri).then(response => {
                this.datas = response.data;
            });
        },
        filterUsersByName: function(datas){
            return datas.filter((item)=>{
                return this.searchQuery.toLowerCase().split(' ').every(v => item.username.toLowerCase().includes(v))
        })
        },
       
        filterUsersByType: function(datas){
            return datas.filter((item)=>{
                return this.searchQuery.toLowerCase().split(' ').every(v => this.getText(item.type).toLowerCase().includes(v))
        })
        },
        detailPost(id, type){
            this.$store.commit('STORE_SUCCESS', id)
            if(type == 1){
                this.$router.push({
                    path: "/informationShop",
                    params: {
                        studentId: id
                    }
                }); 
            }else{
                this.$router.push({
                    path: "/informationCustomer",
                    params: {
                        studentId: id
                    }
                }); 
            }
             
                
        },
        deletePost(id) {
            let uri = `http://127.0.0.1:8000/api/post/delete/${id}`;
            this.axios.delete(uri).then(response => {
                this.datas.splice(
                    this.datas.map(item => item.id).indexOf(id),
                    1
                );
                this.$toast.open({
                    message: response.data,
                    type: "success",
                    duration: 1000,
                    dismissible: true
                });
            });
        },
        getText(value) {
            if (value === 1) {
                return "Store";
            } else if (value === 0) {
                return "Customer";
            } else if (value === 2) {
                return "Admin";
            } else {
                return "Defound";
            }
        }
    }
};
</script>
