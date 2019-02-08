<template>
  <div class="">
  <!-- <form class="form-inline mr-2 my-lg-2 mx-auto"> -->
  <!-- </form> -->
  <nav class="navbar navbar-expand-md navbar-dark navbar-color navbar-padding">
        <div class="mx-auto d-flex flex-column justify-content-center">
          <div class="text-center">
            <a class="navbar-brand" id="brand-name" href="#">The Beer Bank</a>
          </div>
          <div class="navbar-text" id="tag-line" href="#">Find your favorite beer here</div>

          <input class="form-control form-control-lg" id="search-box" type="text" placeholder="Search for beer name" aria-label="Search" v-model="searchInput" @input="changeType">
          <a class="navbar-text" id="advanced-search" href="/adsearch">Advanced Search</a>
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
                      <div class="col-lg-4 col-md-6 col-sm-12" style="display: flex"  v-for="(beer, $index) in beers" :key="$index">
                        <div class="col-lg-12 each-beer">
                          <span class="float-right" v-bind:id="'list-' + beer.id" v-on:click="changeColorOrange(beer.id)" v-bind:style="favouriteStyleBlack"><i class="fa fa-star-o" aria-hidden="true"></i></span>

                          <img class="beer-image" v-bind:src="beer.image_url" v-on:click="displayPopup(beer)">
                          <div class="beer-name">
                            {{ beer.name }}
                          </div>
                          <div class="beer-tagline">
                            {{ beer.tagline }}
                          </div>
                        </div>
                      </div>
                 </div>
                 <!-- <ul class="list-group">
                   <li class="list-group-item" v-for="beer in beers">{{ beer.name }}</li>
                 </ul> -->
               <transition name="fade-slow">
               <div class="notification is-info" v-if="end" v-show="!search">
                  <p id="moreBeer" v-on:click="getMoreBeers()">Get More Beers ...</p>
               </div>
              </transition>
            <!-- /end -->
         </div>
      </transition>
      <!-- /content -->
            </div>
        </div>
      </div>

      <vue-snotify></vue-snotify>
      <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler($event)"></infinite-loading>

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
import Vue from 'vue';
import Snotify, { SnotifyPosition } from 'vue-snotify';
import InfiniteLoading from 'vue-infinite-loading';
const options = {
  toast: {
    position: SnotifyPosition.rightTop
  }
};
Vue.use(Snotify, options);
Vue.use(Snotify);
const api = "https://api.punkapi.com/v2/beers";
export default {
  data(){
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
      search: true,
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
      infiniteId: +new Date(),
      }
    },
    watch: {
    // whenever question changes, this function will run
  },
  components: {
    InfiniteLoading,
  },
  created: function () {

  },
    methods: {
      // This method handles infinite loading and scrolling
      infiniteHandler($state) {
        if(!_.isEmpty(this.searchInput)){
          let searchToSnakeCase = _.snakeCase(this.searchInput);
          axios.get(api, {
            params: {
              page: this.page,
              per_page: this.display,
              beer_name: searchToSnakeCase
            },
          }).then(({ data }) => {
            if (data.length) {
              this.page += 1;
              this.beers = data;
              $state.loaded();
              this.show = true;
            }
            else
            {
              $state.complete();
            }
          })
          .catch(function (error) {
            vm.$snotify.success('No Beers to display', 'Message', {
              timeout: 2000,
              showProgressBar: false,
              closeOnClick: false,
              pauseOnHover: true
            });
            });
        }
        else{
          axios.get(api, {
            params: {
              page: this.page,
              per_page: this.display
            },
          }).then(({ data }) => {
            if (data.length) {
              this.page += 1;
              this.beers.push(...data);
              $state.loaded();
              this.show = true;
            }
            else
            {
              $state.complete();
            }
          })
          .catch(function (error) {
            vm.$snotify.success('No Beers to display', 'Message', {
              timeout: 2000,
              showProgressBar: false,
              closeOnClick: false,
              pauseOnHover: true
            });
            });
        }
   },

   changeType() {
     this.page = 1;
     this.beers = [];
     this.infiniteId += 1;
    },

      // This method handles the Popup Dialog for Beer Details
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
            vm.$snotify.warning('No Beers to display', 'Message', {
              timeout: 2000,
              showProgressBar: false,
              closeOnClick: false,
              pauseOnHover: true
            });
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
        }
        else if(x.innerHTML == "<i class=\"fa fa-star\" aria-hidden=\"true\"></i>"){
          x.style="color: black; cursor: pointer";
          x.innerHTML = "<i aria-hidden=\"true\" class=\"fa fa-star-o\"></i>";
          this.favBeerID = event;
          this.removeFavouriteBeers(this.favBeerID);
        }
      },
      // preform API request to the server
          fetch() {
            var vm = this
            this.beers = [];
             window.setTimeout(() => {
                axios.get('https://api.punkapi.com/v2/beers?page='+vm.page+'&per_page='+vm.display, {
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
                  })
                vm.show = true;
                this.search = false;
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
      this.loadFavouriteBeers();
    }
}
</script>
<!--  -->
