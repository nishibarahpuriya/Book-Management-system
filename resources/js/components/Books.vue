<template>
    <div>
        <h4 class="text-center">All Books
            
        </h4><br/>
            <div class="row">
                <div class="col-sm-8">
                    <form @submit.prevent="searchBook">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search By Title/Author/Genre/isbn" aria-describedby="button-addon2"  ref="search">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>    
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" @click="this.$router.push('/books/add')" style=" height: 35px;">Add Book</button>
                </div>
            </div>
          
            <br/> 
        <table class="table table-bordered" v-if="books != ''">
            <thead>
            <tr>
                <th >S.No</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>isbn</th>
                <th>Published Date</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(book,index) in books" :key="book.id" >
                <td>{{ ((pagination.page-1) * 10)+index+1 }}</td>
                <td>{{ book.title }}</td>
                <td>{{ book.author }}</td>
                <td>{{ book.genre }}</td>
                <td>{{ book.isbn }}</td>
                <td>{{ book.published }}</td>
                <td><img v-bind:src="'/images/books-image/'+book.image" style="height: 40px; width: 50px;"></td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'editbook', params: { id: book.id }}" class="btn btn-primary">Edit
                        </router-link>
                        <button class="btn btn-danger" @click="deleteBook(book.id)">Delete</button>
                    </div>
                </td>
            </tr>
              
            </tbody>
        </table>
            <nav aria-label="Page navigation example" class="d-flex justify-content-center">
                    <ul class="pagination">
                        <li class="page-item"><button class="page-link"  @click="getPageNumber([pagination.previousPage])">Previous</button></li>
                        <li class="page-item" v-for="pagination in pagination.pageButtonArray" :key="pagination">
                            <button class="page-link" @click="getPageNumber([pagination.page_number])">{{pagination.page_number}}</button>
                        </li>
                        <li class="page-item"><button class="page-link" @click="getPageNumber([pagination.nextPage])"> Next</button></li>
                    </ul>
            </nav>
        <TailwindPagination
            :data="laravelData"
            @pagination-change-page="getResults"
        />

        
    </div>
</template>

<script>
export default {
    data() {
        return {
            books: [],
            pagination : [],
            search: ''
        }
    },

    created() {
     
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
             this.$axios.post(`/api/books`)
                .then(response => {
                    this.books = response.data.books;
                    this.pagination =response.data.pagination;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
        getPageNumber(pageNo) {
            this.search = this.$refs.search.value
            let params = {
                'page': pageNo[0],
                'search' : this.search

            };
            
              this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post(`/api/books`, params)
                        .then(response => {
                            this.books = response.data.books;
                            this.pagination =response.data.pagination;
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })

        },

        searchBook() {
            this.search = this.$refs.search.value
                let params = {
                'search': this.search
            };
              this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post(`/api/books`, params)
                        .then(response => {
                            this.books = response.data.books;
                            this.pagination =response.data.pagination;
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
        },
        deleteBook(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.delete(`/api/books/delete/${id}`)
                    .then(response => {
                        let i = this.books.map(item => item.id).indexOf(id); // find index of your object
                        this.books.splice(i, 1)
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>