// global variables
let cart = [], purchaseCost = 0, shippingCosts = 0, selectedLongitude = 111111, selectedLatitude = 22222;


$(function () {
    // waits for the document to load then process this JS

    $('#navToggler').click(toggleSideNav)
})

function toggleSideNav() {
    let toggleClass = '';
    // if($('#sideNav').css('display')=='none'){
    //     //not shown, show now
    //     $('#sideNav').slide
    //    }else{
    //     //shown , hide now
    //     $('#sideNav').removeClass('slideShow').addClass('slideHide');
    //    }
    //todo add css animation to fold in out
    $('#sideNav').slideToggle();
}

function displayItems(items = [], parentSelector = '') {
    let content = '';
    if (Array.isArray(items)) {
        /*loop all items one by one */
        items[0].forEach((item, index) => {
            content +=
                `
                <div class="itemWrapper">
                    <div class="item">
                    <div>
                        <img src='assets/images/${item.image}' class='img-fluid img-thumbnail'/>
                    </div>
                        <div>
                            <div>${item.name}</div>
                            <div>Price: ${item.price}Ksh Color: ${item.color}</div>
                            <div>Short Desc</div>
                            <div class='addToCartBtn d-flex justify-content-center align-items-center bg-dark' data-target='${item.id}'>
                                
                                 <span class='purchaseItemsCounter mx-1 p-1 d-flex' data-target='${item.id}'>
                                    <span data-target='${item.id}' onclick='modifyUserCart(${item.id},{isRemoving:true})' class=''>
                                        <i class='mdi mdi-cart-minus text-warning'></i>
                                    </span>
                                    <span class='mx-1 text-info text-bold addedItemsOfProductCounter' data-target='${item.id}'>0</span>
                                    <span data-target='${item.id}' onclick='modifyUserCart(${item.id},{isAdding:true})' class=''>
                                        <i class='mdi mdi-cart-plus text-success'></i>
                                     </span>
                                </span>

                           </div>
                        </div>
                    </div>
                </div>
            `;/*append new data to existing*/
        })

    } else {
        //no items to display
        console.log('not array');
    }
    $(parentSelector).html(content);

}



function modifyUserCart(target, options = {}) {

    let defaultOptions = {
        isAdding: false,//quantity change checks
        isRemoving: false//quantity change checks
    }
    options = { ...defaultOptions, ...options };//de structuring the object and updating it with user arguments
    //check if product already exists in cart==>then get its index and item
    let itemIndex, i = cart.filter(
        (item, index) => {
            if (item.id === target) {
                itemIndex = index;
                return true;
            }
            return false;
        }
    );


    let clickedCartModifyBtn = $(`.addToCartBtn[data-target='${target}'`);

    if (i.length === 0) {
        //    not existed in cart, add now
        let targetProduct = products.filter((item) => {
            return item.id === target;
        })

        //check if the requested item exists in our products
        if (targetProduct.length === 0) {
            //not found
            notify('Product Not Found');
        } else {
            cart.push({ ...targetProduct[0], quantity: 1 });
            updateUserCartView({ type: 'productAdded', name: targetProduct[0].name, ...options });//show new details now since cart changed
            $(`.addedItemsOfProductCounter[data-target='${target}']`).html(1);
            // show user the item has been added now
            $(clickedCartModifyBtn).addClass('hasProductAdded');

        }

    } else {
        //existed,user targets removing item

        if (!options.isAdding && !options.isRemoving) {
            //confirm from user to remove item
            if (confirm(`Remove ${i[0].name} From your Cart?`)) {
                //defaults to removing item from cart
                let itemName = '';
                cart = cart.filter((item) => {
                    // remove item with this target id ie preserve all that return true here
                    if (item.id !== target) {
                        return true;//preserve this item
                    } else {
                        itemName = item.name;//get item name
                        return false;
                    }
                });

                $(clickedCartModifyBtn).removeClass('hasProductAdded');


                updateUserCartView({ type: 'productRemoved', name: itemName, ...options });//show new details now since cart changed

            } else notify("Item Not removed From Cart");

        } else {
            //check the quantity of this item already in the cart
            if (i[0]?.quantity === undefined) {
                i[0].quantity = 1
            }
            let newQuantity, presentQuantity = parseInt(i[0].quantity);//if the quantity property not there use 1 by default

            if (options.isRemoving) {
                newQuantity = presentQuantity - 1;

            } else {
                newQuantity = presentQuantity + 1;

            }
            $(`.addedItemsOfProductCounter[data-target='${target}']`).html(newQuantity);

            cart[itemIndex].quantity = newQuantity;
            updateUserCartView({ type: 'productModified', name: i[0].name, ...options });//show new details now since cart changed

        }
    }
}

function updateUserCartView(options) {
    $('.cartCounter').html(cart.length);//display number of current items
    let msg = '', cls = 'info';
    switch (options.type) {
        case 'productModified':
            msg = ` Successfully Modified Cart Item`;
            cls = 'info';
            break;

        case 'productAdded':

            msg = `${options.name} Successfully Added To Your Cart`;
            cls = 'success';
            break;

        case 'productRemoved':
            msg = `${options.name} Successfully Removed From Your Cart`;
            cls = 'warning';
            break;


        default:
            console.log('unknown cart operation');
            break;
    }
    // display message to user
    notify(msg, { className: cls, position: 'top left' });

    //add cart to cookie for persistent storage
    setCookie('cart', JSON.stringify(cart));

    calculateTotals({ isFinal: true });
}

function calculateTotals(options) {
    getShippingCosts(options,



        (shippingTotal) => {
            //calculate costs of items in cart
            let total = 0;
            cart.forEach(
                (item, index) => {
                    total += (item.price * item.quantity);
                }
            );
            $('#totalCosts').html(`${shippingTotal + total} Ksh`);
        }


    );

}
function toggleMapView() {
    $('#mapView').slideToggle();
}

function confirmCartDetails() {
    //create html to display
    if (cart.length == 0) {
        //cart is empty,return now
        notify('Your Cart is Empty!', { className: 'error' });
        return;
    }

    cart.forEach((item) => {

    })
}
/*======================General Functions====================*/
function setCookie(cname, cvalue, extime, secure = false, path = '/') {
    var secure = secure == true ? ';secure' : '';
    var path = path == '/' ? '/' : ';path=' + path;
    var d = new Date();
    d.setTime(d.getTime() + (extime));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + secure + path;
}

function getShippingCosts(options, callbackFn = () => { }) {
    options = { isFinal: true, ...options };

    //if is final request make an ajax request to get costs from server
    if (options.isFinal)
        new AjaxRequest(
            {
                data: {
                    target: 'getShippingCosts',
                    longitude: selectedLongitude,
                    latitude: selectedLatitude,
                },
                loaderOn: '#shippingCostsDisplay'
            },
            (status, data, userdata) => {
                if (!status) {
                    notify('Server Error!', { className: 'error' });
                    returnData(0)
                    return;
                }
                if (!data.success) {
                    returnData(0)
                    return;
                }
                returnData(data.data?.amount);
            })
    else
        returnData(shippingCosts);
    //calculate locally,use default

    function returnData(amount) {
        shippingCosts = amount;
        $('#shippingCostsDisplay').html(amount);
        callbackFn(amount);
    }
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function notify(message, options = {}) {
    //todo add ids to specific notifications for cancelling
    let style = $.notify.getStyle('bootstrap');
    style.html = '<div data-notify-html></div>';
    $.notify.addStyle('bootstrap', style);

    let defaults = {
        clickToHide: true,
        autoHide: true,
        autoHideDelay: 5000,
        position: 'top right',
        elementPosition: 'top right',
        className: 'success',
        showAnimation: 'slideDown',
        hideAnimation: 'slideUp',
    };
    let params = Object.assign(defaults, options);
    $.notify(message, params);

}
function addLoader(parent) {

    $(parent).prepend("<div class='loader-wrapper shadow'></div>").css('position', 'relative').attr('initialPosition', $(parent).css('position'));
    $('.loader-wrapper').css({
        position: 'absolute',
        background: 'rgba(0,0,0,.55)',
        left: 0,
        right: 0,
        top: 0,
        bottom: 0,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        pointerEvents: 'all',
        zIndex: '1000000', cursor: 'progress'

    }).append(`<div class="loader-div">
                            <div class="jumper" style="display: flex;justify-content: center;align-items: center">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                          
                  </div>`);


}

function removeloader(parent) {

    $(parent + ' > .loader-wrapper').fadeOut(300, function () {
        $(parent).children(".loader-wrapper").remove();
        $(parent).css('position', $(parent).attr('initialPosition')).removeAttr('initialPosition');
    });


}
class AjaxRequest {
    params = {};
    _userData = {};
    sendStatus = false;
    errorDescription;
    responseObject = {};
    completed = false;
    _callback;

    constructor(requestObject, _callback, _userData) {
        let _userDataDefaults = {
            toCache: true,
            cacheDurationInSecs: 60,
            forceCacheRebuild: false,//force cache rebuild
            ..._userData,

        };

        this._userData =
        {
            ..._userDataDefaults,

            dataName: JSON.stringify(requestObject?.data)
        };

        this._callback = _callback;

        let defaults = {
            loaderOn: '',
            beforeSend: () => {

                if (requestObject?.loaderOn) {
                    addLoader(requestObject.loaderOn)
                }
            },
            url: 'controller/ajax.php',
            data: {},
            method: 'post',
            success: '',
            complete: this.done,
            sendResponse: this.sendResponse,
            _userData: this._userData,
            _callback: this._callback,
            progressHandler: (p) => {
                $('body').append(`<div id="ajaxProgress " class='progress position-absolute top-0 left-0 right-0'><div></div></div>`)
                $('#ajaxProgress.progress div').css('width', p + '%').html('Processing... ' + p + '%');
                let progressDiv = $(' #ajaxProgress.progress');
                if (p < 100) {
                    progressDiv.slideDown(50);

                } else {
                    progressDiv.slideDown(0);
                    setTimeout(function () {
                        progressDiv.slideUp(500, () => {
                            $(progressDiv).remove();
                        });
                    }, 1000)
                }

            },


        };
        this.params = Object.assign(defaults, requestObject);
        this.params.xhr =
            function () {
                let aj = $.ajaxSettings.xhr();
                if (aj.upload) {
                    aj.upload.addEventListener('progress', (event) => {
                        let percent = 0;
                        let position = event.loaded || event.position;
                        let total = event.total;

                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }

                        this.progressHandler(percent);
                    }
                        , false);
                }
                return aj;
            }
        this.send();
    }

    useLocalStorageData(dataName) {
        let data = localStorage.getItem(dataName);
        if (!data) return false;//not found,fetch again
        try {
            data = JSON.parse(data);
            let now = (new Date()).getTime();
            if (data.expiry > now) {
                //data still valid
                if (this._userData.forceCacheRebuild) return false; //check if force cache rebuild,force fetch
                this.responseObject = data.data;
                return true;
            }
            return false;//expired
        } catch (e) {
            //some json err
            return false;
        }

    }

    send() {
        //check if data is available in local storage

        if (this._userData.toCache && this.useLocalStorageData(this._userData.dataName)) {
            //check if backup is available in cache
            this.done(this.responseObject, true, true);
        } else {
            $.ajax(this.params);
        }

    }

    done(data, status, fromCache = false) {

        this.completed = true;
        let j = {};
        let r;
        try {
            j = data.responseJSON;
            this.responseObject = j;
            this.sendStatus = true;
            r = true;
            //if cache is on cache the data
            //add this fresh data to local storage
            //add only if success
            if (typeof j == "object" && j?.success && this._userData.toCache && !fromCache && 1 === 2) {//todo caching diasbled here
                let expiry = (new Date()).getTime() + this._userData.cacheDurationInSecs * 1000;
                localStorage.setItem(this._userData.dataName, JSON.stringify(
                    {
                        expiry: expiry,
                        data: j,
                        requestOptions: { ...this._userData, dataName: undefined }
                    }
                ));


            }


        } catch (error) {
            this.sendStatus = false;
            this.responseObject = data;
            this.errorDescription = "JSON not Properly Sent Over,Server Error";
            r = false;
        }

        this.sendResponse(r, j);
        return status;
    }

    sendResponse(status, data) {
        this.loaderOn?.length > 0 && removeloader(this.loaderOn);
        return this._callback(status, data, this._userData);

    }

    getStatus() {
        return this.sendStatus;
    }


}

//definging functions
//1.
function name(params) {

}

//2

var subtract = function (params) { }

//3.arrow functions

var arrFunc = (a, b, c) => {
    console.log(a + b + c);
}

