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
        </div>
      </nav>

      <div class="container-fluid container-padding">
        <div class="row">

          <div class="col-lg-3 col-sm-12">

                <div class="card">
                    <article class="card-group-item">
                        <header class="card-header">
                          <h6 class="title">Search Filter </h6>
                        </header>
                    <div class="filter-content">
                    <div class="card-body">

                      <div class="form-row">

                        <div class="form-group col-md-12">
                          <h6 class="title">IBU </h6>
                        </div>

                          <div class="form-group col-md-6">
                            <label>Min</label>
                            <input type="number" class="form-control" id="inputEmail4" v-model="ibuMin">
                          </div>
                          <div class="form-group col-md-6 text-right">
                            <label>Max</label>
                            <input type="number" class="form-control" v-model="ibuMax">
                          </div>
                      </div>


                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <h6 class="title">ABV </h6>
                          </div>
                            <div class="form-group col-md-6">
                              <label>Min</label>
                              <input type="number" class="form-control" id="inputEmail4" v-model="abvMin">
                            </div>
                            <div class="form-group col-md-6 text-right">
                              <label>Max</label>
                              <input type="number" class="form-control" v-model="abvMax">
                            </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <h6 class="title">EBC </h6>
                          </div>
                            <div class="form-group col-md-6">
                              <label>Min</label>
                              <input type="number" class="form-control" id="inputEmail4" v-model="ebcMin">
                            </div>
                            <div class="form-group col-md-6 text-right">
                              <label>Max</label>
                              <input type="number" class="form-control" v-model="ebcMax">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <h6 class="title">BREWED BEFORE </h6>
                              <!--<input type="text" class="form-control" id="brewedB4" placeholder="Brewed before"> -->
                              <datepicker id="brewedB4" placeholder="Brewed before" :format="customFormatter" v-model="brewdB4"></datepicker>
                            </div>
                            <div class="form-group col-md-12">
                              <h6 class="title">BREWED AFTER </h6>
                              <!-- <input type="text" class="form-control" id="brewedAfter" placeholder="Brewed after"> -->
                              <datepicker id="brewedAfter" placeholder="Brewed after" :format="customFormatter" v-model="brewdAft"></datepicker>
                            </div>
                        </div>
                    </div> <!-- card-body.// -->
                    </div>
                    </article> <!-- card-group-item.// -->
                    </div> <!-- card.// -->

                    <vue-snotify></vue-snotify>

          </div>
          <div class="col-lg-8">
              <div class="row">
                  <p class="text-center adsearch-text" id="search-result">Search Result</p>
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
                     <div class="notification is-info" v-if="end">
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
          </div>
      </div>


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
var moment = require('moment');
import Datepicker from 'vuejs-datepicker';
export default {
  data: function(){
    return {
      searchQuery: '',
      ibuMin: '',
      ibuMax: '',
      abvMin: '',
      abvMax: '',
      ebcMin: '',
      ebcMax: '',
      brewdB4: '',
      brewdAft: '',
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
      beerFoodPairings: '',
      show   : true, // display content after API request
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

    ibuMin: function(newValue, oldValue){
      this.debouncedSearchBeerUsingFilter('ibuMin',newValue);
    },
    ibuMax: function(newValue, oldValue){
      this.debouncedSearchBeerUsingFilter('ibuMax',newValue);
    },
    abvMin: function(newValue, oldValue){
      this.debouncedSearchBeerUsingFilter('abvMin',newValue);
    },
    abvMax: function(newValue, oldValue){
      this.debouncedSearchBeerUsingFilter('abvMax',newValue);
    },
    ebcMin: function(newValue, oldValue){
      this.debouncedSearchBeerUsingFilter('ebcMin',newValue);
    },
    ebcMax: function(newValue, oldValue){
      this.debouncedSearchBeerUsingFilter('ebcMax',newValue);
    },
    brewdB4: function(newValue, oldValue){
      this.debouncedSearchBeerUsingFilter('brewdB4',newValue);
      let formattedDate = moment(newValue).format('MM-YYYY');
    },
    brewdAft: function(newValue, oldValue){
      this.debouncedSearchBeerUsingFilter('brewdAft',newValue);
      let formattedDate = moment(newValue).format('MM-YYYY');
    },
  },
  created: function () {
    this.debouncedSearchBeerUsingFilter = _.debounce(this.getSearchResult, 500);
  },

  components: {
    Datepicker
  },
  computed: {
      // slice the array of data to display
      sliced() {
         return this.beers.slice(0, this.display);
      },
   },
    methods: {
      getSearchResult:  function (modelName, newSearch) {
        this.searchQuery = "";
        if(modelName == "ibuMin")
        {
          if(!_.isEmpty(newSearch)){
            if(_.isEmpty(this.searchQuery))
            {
                this.searchQuery = "ibu_lt="+newSearch;
            }else{
              this.searchQuery = this.searchQuery + "&"+"ibu_lt="+newSearch;
            }
          }
        }
        else
        {
            if(!_.isEmpty(this.ibuMin))
            {
              if(_.isEmpty(this.searchQuery))
              {
                  this.searchQuery = "ibu_lt="+this.ibuMin;
              }
              else
              {
                this.searchQuery = this.searchQuery + "&"+"ibu_lt="+this.ibuMin;
              }
            }
        }

        if(modelName == "ibuMax")
        {
          if(!_.isEmpty(newSearch)){
            if(_.isEmpty(this.searchQuery))
            {
                this.searchQuery = "ibu_gt="+newSearch;
            }else{
              this.searchQuery = this.searchQuery + "&"+"ibu_gt="+newSearch;
            }
          }
        }
        else
        {
            if(!_.isEmpty(this.ibuMax))
            {
              if(_.isEmpty(this.searchQuery))
              {
                  this.searchQuery = "ibu_gt="+this.ibuMax;
              }
              else
              {
                this.searchQuery = this.searchQuery + "&"+"ibu_gt="+this.ibuMax;
              }
            }
        }

        if(modelName == "abvMin")
        {
          if(!_.isEmpty(newSearch)){
            if(_.isEmpty(this.searchQuery))
            {
                this.searchQuery = "abv_lt="+newSearch;
            }else{
              this.searchQuery = this.searchQuery + "&"+"abv_lt="+newSearch;
            }
          }
        }
        else
        {
            if(!_.isEmpty(this.abvMin))
            {
              if(_.isEmpty(this.searchQuery))
              {
                  this.searchQuery = "abv_lt="+this.abvMin;
              }
              else
              {
                this.searchQuery = this.searchQuery + "&"+"abv_lt="+this.abvMin;
              }
            }
        }

        if(modelName == "abvMax")
        {
          if(!_.isEmpty(newSearch)){
            if(_.isEmpty(this.searchQuery))
            {
                this.searchQuery = "abv_gt="+newSearch;
            }else{
              this.searchQuery = this.searchQuery + "&"+"abv_gt="+newSearch;
            }
          }
        }
        else
        {
            if(!_.isEmpty(this.abvMax))
            {
              if(_.isEmpty(this.searchQuery))
              {
                  this.searchQuery = "abv_gt="+this.abvMax;
              }
              else
              {
                this.searchQuery = this.searchQuery + "&"+"abv_gt="+this.abvMax;
              }
            }
        }

        if(modelName == "ebcMin")
        {
          if(!_.isEmpty(newSearch)){
            if(_.isEmpty(this.searchQuery))
            {
                this.searchQuery = "ebc_lt="+newSearch;
            }else{
              this.searchQuery = this.searchQuery + "&"+"ebc_lt="+newSearch;
            }
          }
        }
        else
        {
            if(!_.isEmpty(this.ebcMin))
            {
              if(_.isEmpty(this.searchQuery))
              {
                  this.searchQuery = "ebc_lt="+this.ebcMin;
              }
              else
              {
                this.searchQuery = this.searchQuery + "&"+"ebc_lt="+this.abvMax;
              }
            }
        }

        if(modelName == "ebcMax")
        {
          if(!_.isEmpty(newSearch)){
            if(_.isEmpty(this.searchQuery))
            {
                this.searchQuery = "ebc_gt="+newSearch;
            }else{
              this.searchQuery = this.searchQuery + "&"+"ebc_gt="+newSearch;
            }
          }
        }
        else
        {
            if(!_.isEmpty(this.ebcMax))
            {
              if(_.isEmpty(this.searchQuery))
              {
                  this.searchQuery = "ebc_gt="+this.ebcMax;
              }
              else
              {
                this.searchQuery = this.searchQuery + "&"+"ebc_gt="+this.ebcMax;
              }
            }
        }

        if(modelName == "brewdB4")
        {
          let newSearch = moment(newSearch).format('MM-YYYY');
          if(!_.isEmpty(newSearch)){
            if(_.isEmpty(this.searchQuery))
            {
                this.searchQuery = "brewed_before="+newSearch;
            }else{
              this.searchQuery = this.searchQuery + "&"+"brewed_before="+newSearch;
            }
          }
        }
        else
        {
            let brewedB4 = $('#brewedB4').val();
            if(!_.isEmpty(brewedB4))
            {
              if(_.isEmpty(this.searchQuery))
              {
                  this.searchQuery = "brewed_before="+brewedB4;
              }
              else
              {
                this.searchQuery = this.searchQuery + "&"+"brewed_before="+brewedB4;
              }
            }
        }

        if(modelName == "brewdAft")
        {
          let newSearch = moment(newSearch).format('MM-YYYY');
          if(!_.isEmpty(newSearch)){
            if(_.isEmpty(this.searchQuery))
            {
                this.searchQuery = "brewed_after="+newSearch;
            }else{
              this.searchQuery = this.searchQuery + "&"+"brewed_after="+newSearch;
            }
          }
        }
        else
        {
          let brewedAfter = $('#brewedAfter').val();
            if(!_.isEmpty(brewedAfter))
            {
              if(_.isEmpty(this.searchQuery))
              {
                  this.searchQuery = "brewed_after="+brewedAfter;
              }
              else
              {
                this.searchQuery = this.searchQuery + "&"+"brewed_after="+brewedAfter;
              }
            }
        }

        if(!_.isEmpty(this.searchQuery))
        {
          this.page = 1;
          this.display = 25;
          this.fetch(this.searchQuery);
        }
      },
      customFormatter(date) {
          return moment(date).format('MM-YYYY');
      },
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

      getMoreBeers: function(){
        this.show = false;
        this.end = false;
        this.page = this.page + 1;
        location.href="#search-result";
        this.fetch(this.searchQuery);
        if(this.beers.length > this.display - 1){
          this.end = true;
        }
        else{
          this.end = false;
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
          fetch(searchQuery) {
            this.show = false;
            this.beers = [];
            var vm = this
             window.setTimeout(() => {
               axios.get('https://api.punkapi.com/v2/beers?'+searchQuery+'&page='+this.page+'&per_page='+this.display, {
               })
                  .then(function (response) {
                    vm.beers = response.data;
                    if(vm.beers.length > vm.display - 1){
                      vm.end = true;
                    }
                    else{
                      vm.end = false;
                    }
                    if(_.isEmpty(vm.beers))
                    {

                    }
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
             }, 2000);
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
    }
}
</script>
