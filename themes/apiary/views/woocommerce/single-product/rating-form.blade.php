<div class="comment-form-rating">
    <label for="rating">{!! esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) !!}</label>
    <span class="flex" x-data="
        {
            rating: 0,
            hoverRating: 0,
            ratings: [
                {'amount': 1, 'label':'{{ esc_attr__('Very poor', 'woocommerce') }}'},
                {'amount': 2, 'label':'{{ esc_attr__('Not that bad', 'woocommerce') }}'},
                {'amount': 3, 'label':'{{ esc_attr__('Average', 'woocommerce') }}'},
                {'amount': 4, 'label':'{{ esc_attr__('Good', 'woocommerce') }}'},
                {'amount': 5, 'label':'{{ esc_attr__('Perfect', 'woocommerce') }}'}
            ],
            rate(amount) {
                if (this.rating == amount) {
                    this.rating = 0;
                } else {
                    this.rating = amount;
                }
            },
            currentRating() {
                return this.rating;
            },
            currentLabel() {
                let r = this.rating;
                if (this.hoverRating != this.rating) r = this.hoverRating;
                let i = this.ratings.findIndex(e => e.amount == r);
                if (i >=0) {return this.ratings[i].label;} else {return ''};
            }
        }
        ">
        <input name="rating" type="hidden" value="0" x-model.number="currentRating()" required>
        <span class="flex flex-col justify-center">
            <span class="flex -ml-2">
                <template x-for="(star, index) in ratings" :key="index">
                    <span @click="rate(star.amount)" @mouseover="hoverRating = star.amount" @mouseleave="hoverRating = rating"
                          aria-hidden="true"
                          :title="star.label"
                          class="rounded-xs text-gray-300 fill-current focus:outline-hidden focus:shadow-outline p-1 w-5 m-0 cursor-pointer"
                          :class="{'text-yellow-400': hoverRating >= star.amount, 'text-yellow-400': rating >= star.amount && hoverRating >= star.amount}">
                            <svg class="w-5 transition duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </span>
                </template>
            </span>
            <div class="rating-label prose prose-sm text-gray-500">
                <template x-if="rating || hoverRating">
                    <div x-text="currentLabel()"></div>
                </template>
                <template x-if="!rating && !hoverRating">
                    <div>{{ esc_html__('Rate&hellip;', 'woocommerce') }}</div>
                </template>
            </div>
        </span>
    </span>
</div>