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
        this.isMobile = false;

        //Modification du DOM
        this.root = this.createDivWithClass('slider');
        this.slider_large = this.createDivWithClass('slider_large');
        this.root.setAttribute('tabIndex', '0');
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

        // responsive
        this.onWindowResize();
        window.addEventListener('resize', this.onWindowResize.bind(this));
        this.root.addEventListener('keyup', e => {
            if (e.key === 'ArrowRight'){
                this.next();
            }else if (e.key == 'ArrowLeft') {
                this.prev();
            }
        })
    }

    setStyle () {
        let ratio = this.items.length / this.slidesVisible;
        this.slider_large.style.width = (ratio * 100) + "%";
        this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + "%");
    }

    createNavigation () {
        let nextButton = this.createDivWithClass('slider_next');
        let prevButton = this.createDivWithClass('slider_prev');
        this.root.appendChild(nextButton);
        this.root.appendChild(prevButton);
        nextButton.addEventListener('click', this.next.bind(this));
        prevButton.addEventListener('click', this.prev.bind(this));
    }

    onWindowResize () {
        let mobile = window.innerWidth < 768;
        if (mobile !== this.isMobile) {
            this.isMobile = mobile;
            this.setStyle();
        }
    }


    next () {
        this.goToItem(this.currentItem + this.slidesToScroll);
    }

    prev () {
        this.goToItem(this.currentItem - this.slidesToScroll);
    }
    /**
    * Déplace le slider a l'élément voulu
    * @param {number} index
    */
    goToItem (index) {
        if (index < 0) {
            index = this.items.length - this.options.slidesVisible;
        } else if (index >= this.items.length || this.items[this.currentItem + this.options.slidesVisible] === undefined && index > this.currentItem){
            index = 0;
        }
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

    get slidesToScroll () {
        return this.isMobile ? 1 : this.options.slidesToScroll;
    }
    get slidesVisible () {
        return this.isMobile ? 2 : this.options.slidesVisible;
    }
}


document.addEventListener('DOMContentLoaded', function() {

    new Slider (document.querySelector('#slider_most_viewed'), {
        slidesToScroll: 2,
        slidesVisible: 4
    })
    new Slider (document.querySelector('#highest_rated'), {
        slidesToScroll: 2,
        slidesVisible: 4
    })
    new Slider (document.querySelector('#most_recent'), {
        slidesToScroll: 2,
        slidesVisible: 4
    })

})
