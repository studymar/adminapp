<?php

/* 
 * Shows the Pagination for a List
 * Preconditions:
 * - Parent must have an property-object, which is give as prop "pagination":
 *   pagination: {
 *     activePage: 0,
 *     pageSize: 20
 *   }
 * - Parent must have a function, which is loading the data and using the activePage and pageSize property
 *   It is called, after clicking the pagination page-icons
 *   getItems() {
 */

?>
<template id="paginationcomponent">

    <!-- Pagination -->
    <nav v-if="pagination">
      <ul class="pagination pagination-sm justify-content-end">
        <li class="page-item" v-if="pagination.activePage>0">
          <a class="page-link" href="#" aria-label="Previous" v-on:click.prevent="getItemsOnPage(pagination.activePage-1)">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
          </a>
        </li>
        <li class="page-item"><a class="page-link" href="#" v-on:click.prevent="getItemsOnPage(pagination.activePage-1)" v-if="pagination.activePage>0">{{pagination.activePage}}</a></li>
        <li class="page-item active"><a class="page-link" v-on:click.prevent="getItemsOnPage(pagination.activePage)" href="#">{{pagination.activePage+1}}</a></li>
        <li class="page-item"><a class="page-link" href="#" v-on:click.prevent="getItemsOnPage(pagination.activePage+1)" v-if="pagination.activePage < pagination.lastPage">{{pagination.activePage+2}}</a></li>
        <li class="page-item" v-if="pagination.activePage < pagination.lastPage">
          <a class="page-link" href="#" aria-label="Next" v-on:click.prevent="getItemsOnPage(pagination.activePage+1)">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
        </li>
      </ul>
    </nav>    

</template>

<script type="text/javascript">
var paginationcomponent = {
    template: '#paginationcomponent',
    props: {
        pagination: Object
    },
    components: {
    },
    data: function(){
        return {
        }
    },
    mounted: function() {
    },
    methods: {
        getItemsOnPage(pageNo){
            this.pagination.activePage = pageNo
            this.$parent.getItems()
        }
    }
}

</script>

