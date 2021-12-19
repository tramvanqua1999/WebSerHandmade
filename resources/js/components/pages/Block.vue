<template>
    <div class="content-wrapper">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Chặn tài khoản</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <router-link to="/home">Home</router-link>
                                </li>
                                <li class="breadcrumb-item active">Chặn tài khoản cửa hàng hoặc khách hàng</li>
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
                                    <!-- <br />
                                    <router-link
                                        :to="{ path: '/create' }"
                                        style="width:80px"
                                        class="btn btn-success waves-effect waves-light m-r-10"
                                        >Add
                                    </router-link>
                                    <br /> -->
                                    <br />
                                    <br />
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            Danh sách tài khoản cửa hàng hoặc khách hàng
                                                        </h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div>
                                                            <vue-good-table
                                                                :columns="columns"
                                                                :rows="datas"
                                                                :sort-options="{
                                                                    enabled: true,
                                                                    initialSortBy: {field:'id',type:'asc'}
                                                                }"
                                                                :search-options="{
                                                                    enabled: true
                                                                }">
                                                                <template
                                                                    slot="table-row"
                                                                    slot-scope="props">
                                                                    <span v-if="props.column.field =='type'">
                                                                        <span>{{getText(props.row.type)}}</span>
                                                                    </span>
                                                                    <span v-else-if="props.column.field =='function'">
                                                                        <button class="btn btn-warning"
                                                                            @click.prevent="Block(props.row.id)">
                                                                            <i class="fas fa-lock"></i>
                                                                        </button>
                                                                    </span>
                                                                    <span v-else>
                                                                        {{props.formattedRow[props.column.field]}}
                                                                    </span>
                                                                </template>
                                                            </vue-good-table>
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

<script>
import "vue-good-table/dist/vue-good-table.css";
import { VueGoodTable } from "vue-good-table";

// add to component

export default {
    name: "Block",
    components: {
        VueGoodTable
    },
    data() {
        return {
            columns: [
                {
                    label: "ID",
                    field: "id",
                    type: "number",
                    width: "80px"
                },
                {
                    label: "Tài khoản",
                    field: "username"
                },
                {
                    label: "Loại(s)",
                    field: "type",
                    type: "number"
                },
                {
                    label: "Chặn tài khoản",
                    field: "function",
                    filterable: true
                }
            ],
            interval: null,
            datas: []
        };
    },
    created() {
        this.interval = setInterval(this.refreshData, 1000);
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    methods: {
        refreshData() {
            let uri = "http://127.0.0.1:8000/api/block";
            this.axios.get(uri).then(response => {
                this.datas = response.data;
            });
        },
        Block(id) {
            let uri = `http://127.0.0.1:8000/api/post/block/${id}`;
            this.axios.post(uri).then(response => {
                this.datas.splice(
                    this.datas.map(item => item.id).indexOf(id),
                    1
                ); 
                if(response.status == 200){
                    this.$toast.open({
                        message: response.data,
                        type: "success",
                        duration: 1000,
                        dismissible: true
                    });
                }
               
            });
        },
        getText(value) {
            if (value === 1) {
                return "Store";
            } else if (value === 0) {
                return "Customer";
            }else {
                return "Admin";
            }
        }
    }
};
</script>
