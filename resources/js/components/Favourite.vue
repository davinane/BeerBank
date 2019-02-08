<template>
  <div class="">

    <nav class="navbar navbar-expand-md navbar-dark navbar-color navbar-padding">
          <div class="mx-auto d-flex flex-column justify-content-center">
            <div class="text-center">
              <a class="navbar-brand" id="brand-name" href="#">The Beer Bank</a>
            </div>
            <div class="navbar-text" id="tag-line" href="#">Find your favorite beer here</div>
          </div>
        </nav>

      <div class="container beerlist-padding" id="bear-listings">
        <div class="row">
            <div class="col-md-12 col-lg-12">
              <!-- preloader -->
               <transition name="fade">
                  <div class="preloader" v-if="!show">
                     <span class="loader"></span>
                  </div>
               </transition>
               <!-- /preloader -->
             </div>
             <div class="col-md-12 col-lg-12">
               <transition name="slide-fade">
               <div id="listbeers" v-if="show">
                   <div class="row">
                      <div class="col-lg-4 col-md-6 col-sm-12" style="display: flex"  v-for="beer in beers">
                        <div class="col-lg-12 each-beer" data-container="body" data-toggle="popover" data-placement="top" title="Beer Details" data-content="Click on the image to see beer details">
                          <span class="float-right" v-bind:id="'list-' + beer.id" v-on:click="changeColorOrange(beer.id)" v-bind:style="favouriteStyleOrange"><i class="fa fa-star" aria-hidden="true"></i></span>

                          <img class="beer-image" v-bind:src="beer.image_url" v-on:click="displayPopup(beer)">
                          <div class="beer-name">
                            {{ beer.name }}
                          </div>
                          <div class="beer-tagline">
                            {{ beer.tagline }}
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-12">
                      <transition name="fade-slow">
                        <div class="notification is-info" v-show="showEmpty">
                           <p>Your favourite list is empty</p>
                        </div>
                      </transition>
                    </div>
                 </div>
                 <!-- <ul class="list-group">
                   <li class="list-group-item" v-for="beer in beers">{{ beer.name }}</li>
                 </ul> -->
            <!-- /end -->
         </div>
      </transition>
      <!-- /content -->
            </div>
        </div>
      </div>

      <vue-snotify></vue-snotify>

      <div class="modal" tabindex="-1" role="dialog" id="beerDetail">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-lg-3">
                    <img id="eachbeer-img" v-bind:src="beerImg"/>
                  </div>

                  <div class="col-lg-8">
                    <h2 class="beer-name">{{ beerTitle }}</h2>
                    <h5 class="beer-tagline">{{ beerTagline }}</h5>
                    <hr class="hr-separator">
                    <div class="others-tags">
                      <strong>IBU: </strong><span>{{ beerIBU }} &nbsp; &nbsp;</span>
                      <strong>ABV: </strong><span>{{ beerABV }} &nbsp; &nbsp;</span>
                      <strong>EBC: </strong><span>{{ beerEBC }} &nbsp; &nbsp;</span>
                    </div>
                    <div class="beer-desc">
                      <p>{{ beerDESC }}</p>
                    </div>
                    <div class="beer-bestserved">
                      <strong>Best served with:</strong>
                      <ul>
                        <li v-for="foodPair in beerFoodPairings">
                          {{ foodPair }}
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="recommendLikes">
                    <strong id="recommendDirection">You might also like:</strong>
                    <div class="row">
                      <div class="col-lg-4" v-for="eachRecommended in recommendedBeers" style="display: flex">
                        <div class="recommendedBeer text-center" style="flex:1">
                          <img v-bind:src="eachRecommended.image_url" v-bind:alt="eachRecommended.name">
                          <p>{{ eachRecommended.name }}</p>
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
</template>

<script>
import Vue from 'vue'
import Snotify, { SnotifyPosition } from 'vue-snotify'
const options = {
  toast: {
    position: SnotifyPosition.rightTop
  }
}

Vue.use(Snotify, options)
Vue.use(Snotify);
export default {
  data: function(){
    return {
      searchInput:'',
      beers: [], // Server's response data
      recommendedBeers: [],
      errorMsg: '',
      beerImg: '',
      beerTitle: '',
      beerTagline: '',
      beerIBU: '',
      beerABV: '',
      beerEBC: '',
      beerDESC: '',
      showEmpty: false,
      beerFoodPairings: '',
      show   : false, // display content after API request
      offset : 5,     // items to display after scroll
      page: 1,
      display: 25,     // initial items
      trigger: 200,   // how far from the bottom to trigger infinite scroll
      end    : false, // no more resources
      favouriteStyleBlack: "color: black; cursor: pointer;",
      favouriteStyleOrange: "color: orange; cursor: pointer;",
      favBeers: [],
      favBeerID: null,
      }
    },
    watch: {
    // whenever question changes, this function will run
    searchInput: function (newSearch, oldSearch) {
      this.debouncedSearchBeer(newSearch)
    }
  },
  created: function () {
    // get the data by performing API request
      this.fetch();
  },
  computed: {
      // slice the array of data to display
      sliced() {
         return this.beers.slice(0, this.display);
      },
   },
    methods: {
      displayPopup: function(beer){
        this.beerImg = beer['image_url'];
        this.beerTitle = beer['name'];
        this.beerTagline = beer['tagline'];
        this.beerIBU = beer['ibu'];
        this.beerABV = beer['abv'];
        this.beerEBC = beer['ebc'];
        this.beerDESC = beer['description'];
        this.beerFoodPairings = beer['food_pairing'];

        $('#beerDetail').modal();
        let noOfRecommendation = 3;
        let vm = this;
        this.recommendedBeers = [];
        for(let i = 0; i < noOfRecommendation; i++){
        axios.get('https://api.punkapi.com/v2/beers/random', {
        })
          .then(function (response) {
            vm.recommendedBeers.push(response.data[0]);
          })
          .catch(function (error) {
            vm.errorMsg = 'Error! Could not reach the API. ' + error
          });
        }
      },
      changeColorOrange: function(event){
      // `event` is the native DOM event
        //let targetId = event.currentTarget.id;
        var x = document.getElementById('list-'+event);
        if(x.innerHTML=="<i aria-hidden=\"true\" class=\"fa fa-star-o\"></i>"){
            x.style="color: orange; cursor: pointer";
            x.innerHTML = "<i class=\"fa fa-star\" aria-hidden=\"true\">";
            this.favBeerID = event;
            this.addFavouriteBeers();
            this.show = false;
            this.fetch();
        }
        else if(x.innerHTML == "<i class=\"fa fa-star\" aria-hidden=\"true\"></i>"){
          x.style="color: black; cursor: pointer";
          x.innerHTML = "<i aria-hidden=\"true\" class=\"fa fa-star-o\"></i>";
          this.favBeerID = event;
          this.removeFavouriteBeers(this.favBeerID);
          this.show = false;
          this.fetch();
        }
        else if(x.innerHTML == "<i aria-hidden=\"true\" class=\"fa fa-star\"></i>"){
          x.style="color: black; cursor: pointer";
          x.innerHTML = "<i aria-hidden=\"true\" class=\"fa fa-star-o\"></i>";
          this.favBeerID = event;
          this.removeFavouriteBeers(this.favBeerID);
          this.show = false;
          this.fetch();
        }
      },
      scroll() {
         window.onscroll = ev => {
            if (
               window.innerHeight + window.scrollY >=
               (document.body.offsetHeight - this.trigger)
            ) {
               if (this.display < this.beers.length) {
                  this.display = this.display + this.offset;
               }
               else {
                 if(_.isEmpty(this.beers)){
                    this.end = false;
                 }
                 else{
                   this.end = true;
                 }
               }
            }
         };
      },
      // preform API request to the server
          fetch() {
            var vm = this
            vm.beers = [];
             window.setTimeout(() => {
               let pipedBeers = _.join(vm.favBeers, '|');
                 axios.get('https://api.punkapi.com/v2/beers', {
                   params: {
                     ids: pipedBeers
                   }
                 })
                  .then(function (response) {
                    vm.beers = response.data;
                  })
                  .catch(function (error) {
                    vm.$snotify.success('No Beers to display', 'Message', {
                      timeout: 2000,
                      showProgressBar: false,
                      closeOnClick: false,
                      pauseOnHover: true
                    });
                  });
                vm.show = true;
                if(_.isEmpty(vm.favBeers)){
                  vm.showEmpty = true;
                }
             }, 2000);
          },
          loadFavouriteBeers: function(){
            if (localStorage.getItem('favBeers')) {
            try {
              this.favBeers = JSON.parse(localStorage.getItem('favBeers'));
            } catch(e) {
              localStorage.removeItem('favBeers');
            }
          }
          },
          addFavouriteBeers: function() {
            // ensure they actually typed something
            if (!this.favBeerID) {
              return;
            }

            if(_.indexOf(this.favBeers, this.favBeerID) < 0){
              this.favBeers.push(this.favBeerID);
              this.favBeerID = '';
              this.saveFavouriteBeers();
              this.$snotify.success('Added to favourites', 'Message', {
                timeout: 2000,
                showProgressBar: false,
                closeOnClick: false,
                pauseOnHover: true
              });
            }
          },
          removeFavouriteBeers: function(x) {
            _.pull(this.favBeers, x);
            this.saveFavouriteBeers();
            this.$snotify.success('Removed from favourites', 'Message', {
              timeout: 2000,
              showProgressBar: false,
              closeOnClick: false,
              pauseOnHover: true
            });
          },
          saveFavouriteBeers: function() {
            const parsed = JSON.stringify(this.favBeers);
            localStorage.setItem('favBeers', parsed);
          },
  },
    mounted: function(){
      // track scroll event
      this.scroll();
      this.loadFavouriteBeers();
    }
}
</script>
