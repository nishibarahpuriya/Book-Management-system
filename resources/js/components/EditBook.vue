<template>
    <div>
        <h4 class="text-center">Edit Book</h4>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="updateBook">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="book.title">
                    </div>
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" class="form-control" v-model="book.author">
                    </div>
                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" class="form-control" v-model="book.genre">
                    </div>
                    <div class="form-group">
                        <label>Isbn</label>
                        <input type="text" class="form-control" v-model="book.isbn">
                    </div>
                    <div class="form-group">
                        <label>Publisher</label>
                        <input type="text" class="form-control" v-model="book.publisher">
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" @change='uploadPhoto' name="photo" class="form-control">
                        <td><img v-bind:src="'/images/books-image/'+book.image" style="height: 40px; width: 50px;"></td>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" v-model="book.publisher"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Book</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            book: {}
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get(`/api/books/edit/${this.$route.params.id}`)
                .then(response => {
                    this.book = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
    methods: {
     uploadPhoto(e){
              let file = e.target.files[0];
                let reader = new FileReader();  

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                     this.book.photo = reader.result;
                    }              
                     reader.readAsDataURL(file);
                }else{
                    alert('File size can not be bigger than 2 MB')
                }
            },
        updateBook() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post(`/api/books/update/${this.$route.params.id}`, this.book)
                    .then(response => {
                        this.$router.push({name: 'books'});
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