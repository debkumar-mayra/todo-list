<template>
  <v-card>
    <v-layout>
      <v-navigation-drawer
        class="bg-deep-purple"
        theme="dark"
        permanent
      >
        <v-list color="transparent">
          <Link :href="route('home')"><v-list-item prepend-icon="mdi-view-dashboard" title="Todo"></v-list-item></Link>
          <!-- <v-list-item prepend-icon="mdi-account-box" title="Account"></v-list-item>
          <v-list-item prepend-icon="mdi-gavel" title="Admin"></v-list-item> -->
        </v-list>

        <template v-slot:append>
          <div class="pa-2">
           <Link :href="route('logout')" method="post"> <v-btn block>
              Logout
            </v-btn></Link>
          </div>
        </template>
      </v-navigation-drawer>
      <v-main class="min-h-screen">

  
<h1 class="text-4xl p-3">Welcome {{$page.props.auth.user.name}}</h1>

<v-form @submit.prevent="submit" class="mt-16 w-3/4 ml-8 flex">
    <v-text-field label="To Do Name" variant="outlined" v-model="form.todo_name" :error-messages="errors.todo_name"></v-text-field> 
    <v-btn variant="outlined" size="x-large" class="ml-4" type="submit">Add To List</v-btn>
</v-form>


 <v-card  class="ml-8 mt-3 w-7/12" v-for="(todo,index) in todos" :key="todo.id">
    <div class="p-3 flex justify-between" >

        <span class="mr-2" :class="{'line-through':(todo.is_complete == 2)}">#{{index+1}} {{todo.todo_name}} </span>
        <div class="flex items-center" v-if="todo.is_complete == 1">
        <Link :href="route('editTodo',[$page.props.auth.user.id,todo.id])" class="text-orange-400 ml-2">
        <Icon icon="uil:edit" width="24" height="24" />
        </Link>

        <Link :href="route('completeTodo',todo.id)" class="text-green-600 ml-2" preserve-scroll>
        <Icon icon="fluent-mdl2:completed" width="24" height="24" />
        </Link>

        <a href="javascript:void(0)" class="text-red-600 ml-2" @click="deleteTodo(todo.id)">
          <Icon icon="typcn:delete-outline" width="32" height="32" />
        </a>

        </div>
    </div>
  </v-card>






      </v-main>
    </v-layout>
  </v-card>
</template>

<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({ /* options */ });

const {todo=null,todos,errors}= defineProps({todo:Object, todos:Object,errors:Object});

const form = useForm({
    todo_name:null
});

const submit = () =>{
     if(todo != null){
       form.post(route('updateTodo',todo.id));
     }else{
         form.post(route('addTodo'));
     }
     form.reset('todo_name');
}

const deleteTodo = (id) => {
    router.delete(route('deleteTodo',[usePage().props.auth.user.id,id]),{
        preserveScroll: true,
        onBefore: visit => {
            if(!confirm('Are you sure want delete it')){
                return false;
            }
        },
    });
}

onMounted(()=>{
    if(todo!=null){
        form.todo_name = todo.todo_name;
    }


    if (usePage().props.flash.success) {
        toaster.success(usePage().props.flash.success, { position:'top-right'});
    }

     if (usePage().props.flash.error) {
        toaster.error(usePage().props.flash.error, { position:'top-right'});
    }

})


</script>


