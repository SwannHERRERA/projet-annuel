class Slider {

    /**
    * @param {HTMLElement} element
    * @param {Object} option
    * option:  option.slidesToScroll and slidesVisible
    */
    constructor (element, options = {}){
        this.element = element;
        this.options = Object.assign({}, {
            slidesToScroll: 1,
            slidesVisible: 1
        }, options);
        let children = [].slice.call(element.children);
        this.currentItem = 0;
        this.root = this.createDivWithClass('slider');
        this.slider_large = this.createDivWithClass('slider_large');
        this.root.appendChild(this.slider_large);
        this.element.appendChild(this.root);
        this.items = children.map((child) => {
            let item = this.createDivWithClass('slider-item');
            item.appendChild(child);
            this.slider_large.appendChild(item);
            return item;
        })
        this.setStyle();
        this.createNavigation();
    }

    setStyle () {
        let ratio = this.items.length / this.options.slidesVisible;
        this.slider_large.style.width = (ratio * 100) + "%";
        this.items.forEach(item => item.style.width = ((100 / this.options.slidesVisible) / ratio) + "%");
    }

    createNavigation () {
        let nextButton = this.createDivWithClass('slider_next');
        let prevButton = this.createDivWithClass('slider_prev');
        this.slider_large.appendChild(nextButton);
        this.slider_large.appendChild(prevButton);
        nextButton.addEventListener('click', this.next.bind(this));
        prevButton.addEventListener('click', this.prev.bind(this));
    }


    next () {
        this.goToItem(this.currentItem + this.options.slidesToScroll);
    }

    prev () {
        this.goToItem(this.currentItem - this.options.slidesToScroll);
    }
    /**
    * Déplace le slider a l'élément voulu
    * @param {number} index
    */
    goToItem (index) {
        let translateX = index * - 100 / this.items.length;
        this.slider_large.style.transform = 'translate3d(' + translateX + '%, 0, 0)';
        this.currentItem = index;
    }

    /**
    * @param {string} ClassName
    * @returns {HTMLElement}
    */
    createDivWithClass (className) {
        let div = document.createElement('div');
        div.setAttribute('class', className);
        return div;
    }
}


document.addEventListener('DOMContentLoaded', function() {

    new Slider (document.querySelector('#slider_most_viewed'), {
        slidesToScroll: 3,
        slidesVisible: 4
    })

})
console.log('je suis charger');
