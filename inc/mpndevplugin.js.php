<script type="text/javascript">
window.onload = function(){

    let get_avalible_colors = function(){
        return [
            {
                title: 'beige',
                backgraundColor: '#CCC188',
                isSelected: true
            },
            {
                title: 'yellow',
                backgraundColor: '#ECEA41',
                isSelected: false
            },
            {
                title: 'orange',
                backgraundColor: '#FFA600',
                isSelected: false
            },
            {
                title: 'red',
                backgraundColor: '#BD111B',
                isSelected: false
            },
            {
                title: 'blue',
                backgraundColor: '#162E7B',
                isSelected: false
            },
            {
                title: 'light-blue',
                backgraundColor: '#0068A7',
                isSelected: false
            },
            {
                title: 'green',
                backgraundColor: '#276230',
                isSelected: false
            },
            {
                title: 'gray',
                backgraundColor: '#B5B5A7',
                isSelected: false
            },
            {
                title: 'brown',
                backgraundColor: '#6B442A',
                isSelected: false
            },
            {
                title: 'white',
                backgraundColor: '#ffffff',
                isSelected: false
            },
            {
                title: 'black',
                backgraundColor: '#000000',
                isSelected: false
            }
        ];
    }

    let get_available_shapes = function(){
        return [
            {
                id: 1,
                errorMessages: [
                    'Please, fill all dimensions fields with positive numbers.',
                    'Side "A" must be smaller then side "C"',
                    'Side "B" must be smaller then side "D"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= A){
                        that.msg = this.errorMessages[3];
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B >= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return (this.dimensions[0].value * this.dimensions[3].value) / measurment;
                },
                svg_name: '1.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            },
            {
                id: 2,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    'Side "A" must be smaller then side "C"',
                    'Side "B" must be greater then side "D"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= A){
                        that.msg = this.errorMessages[3];
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B <= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return (this.dimensions[0].value * this.dimensions[1].value) / measurment;
                },
                svg_name: '2.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            },
            {
                id: 3,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    '"d" cannot be greater then side "D"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let C = this.dimensions[0].value | 0;
                    let D = this.dimensions[1].value | 0;
                    let d = this.dimensions[2].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= C){
                        that.msg = this.errorMessages[2];
                    }
                    if(d >= D){
                        that.msg = this.errorMessages[1];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return ((this.dimensions[1].value + (this.dimensions[2].value * 2)) * this.dimensions[0].value) / measurment;
                },
                svg_name: '3.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'd',
                        value: 0
                    }
                ]
            },
            {
                id: 4,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    '"b" cannot be greater then side "B"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let B = this.dimensions[0].value | 0;
                    let b = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= C){
                        that.msg = this.errorMessages[2];
                    }
                    if(b >= B){
                        that.msg = this.errorMessages[1];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return ((this.dimensions[0].value + (this.dimensions[1].value * 2)) * this.dimensions[2].value) / measurment;
                },
                svg_name: '4.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'b',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    }
                ]
            },
            {
                id: 5,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    'Side "A" must be smaller then side "C"',
                    'Side "B" must be bigger then side "D"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= A){
                        that.msg = this.errorMessages[3];
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B <= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return (this.dimensions[2].value * this.dimensions[3].value) / measurment;
                },
                svg_name: '5.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            },
            {
                id: 6,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    'Side "A" must be smaller then side "C"',
                    'Side "B" must be smaller then side "D"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= A){
                        that.msg = this.errorMessages[3];
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B >= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return (this.dimensions[2].value * this.dimensions[1].value) / measurment;
                },
                svg_name: '6.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            },
            {
                id: 7,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    'Side "A" must be smaller then side "C"',
                    'Side "B" must be smaller then side "D"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= A){
                        that.msg = this.errorMessages[3];
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B >= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return (this.dimensions[0].value * this.dimensions[3].value) / measurment;
                },
                svg_name: '7.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            },
            {
                id: 8,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    'Side "A" must be smaller then side "C"',
                    'Side "B" must be bigger then side "D"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= A){
                        that.msg = this.errorMessages[3];
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B <= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return (this.dimensions[0].value * this.dimensions[1].value) / measurment;
                },
                svg_name: '8.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            },
            {
                id: 9,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    'Side "B" must be bigger then side "D"',
                    '"b" must be smaller then side "B"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let b = this.dimensions[2].value | 0;
                    let C = this.dimensions[3].value | 0;
                    let D = this.dimensions[4].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= A){
                        that.msg = this.errorMessages[3];
                    }
                    if(B <= D){
                        that.msg = this.errorMessages[1];
                    }
                    if(b >= B){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return ((this.dimensions[1].value + (this.dimensions[2].value * 2)) * this.dimensions[3].value) / measurment;
                },
                svg_name: '9.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'b',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            },
            {
                id: 10,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    'Side "B" must be smaller then side "D"',
                    '"d" must be smaller then side "D"',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let d = this.dimensions[3].value | 0;
                    let D = this.dimensions[4].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= A){
                        that.msg = this.errorMessages[3];
                    }
                    if(B >= D){
                        that.msg = this.errorMessages[1];
                    }
                    if(d >= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return ((this.dimensions[4].value + (this.dimensions[3].value * 2)) * this.dimensions[2].value) / measurment;
                },
                svg_name: '10.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'd',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            },
            {
                id: 11,
                errorMessages: [
                    'Please, fill all dimensions fields.',
                    'Is impossible for the door to start in that point.'
                ],
                validate: function(that, door){
                    let C = this.dimensions[0].value | 0;
                    if(door.included && (door.width + (door.start | 0)) >= C){
                        that.msg = this.errorMessages[1];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that);
                    });
                },
                area: function(measurment){
                    return (this.dimensions[0].value * this.dimensions[1].value) / measurment;
                },
                svg_name: '11.svg',
                dimensions: [
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : this.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    }
                ]
            }
        ];
    }

    let get_wall_tab = function(){
        return {
            id: 0,
            errorMessages: [
                'Please, fill the door starting point.',
                'Door starting point, must be positive number.',
                'Sorry, the description cannot be longer then 255 characters.'
            ],
            validate: function(that){
                if(this.door_included){
                    that.msg = Number.isInteger(this.door_starts_from | 0) ? that.msg : this.errorMessages[0];
                    that.msg = (this.door_starts_from | 0) > 0 ? that.msg : this.errorMessages[1];
                }
                if(this.section_additional_information.length > 255){
                    that.msg = this.errorMessages[2];
                }
                this.available_shapes.map( shape => { 
                    if(shape.id === this.current_shape_id){
                        shape.validate(that, {
                            included: this.door_included,
                            start: this.door_starts_from,
                            width: that.door_width,
                            height: that.door_height
                        });
                    }
                });
            },
            area: function(shapeId, measurment){
                return (this.available_shapes.filter(function(s){
                    return s.id == shapeId
                })[0].area(measurment) / (measurment == 1 ? 10000 : 1));
            },
            get_door_price: function(door_price){
                return this.door_included ? door_price : 0;
            },
            door_included: '',
            door_starts_from: 0,
            available_shapes: get_available_shapes(),
            current_svg_name: '1.svg',
            section_additional_information: '',
            current_shape_id: 1
        };
    }

    new Vue({
        el: '#setup_order_box',
        data: {
            /* get from db with php */
            price_per_squear_methers: <?php echo $price_per_squear_methers; ?>,
            door_price: <?php echo $door_price; ?>,
            door_width: <?php echo $door_width; ?>,
            door_height: <?php echo $door_height; ?>,
            myUrl: '<?php echo $myUrl; ?>',
            /* get from db with php */
            avalible_colors: get_avalible_colors(),
            wall_tabs: [get_wall_tab()],
            current_wall_tab_id: 1,
            imageIsSelected: false,
            price: 0,
            msg: 'valid',

            measurment: 'in',
            image: null,
            selected_color: 'beige',
            walls: null
        },
        created: function(){
            let counter = 1;
            this.wall_tabs.map(t => { t.id = counter; counter++; });
        },
        computed: {
            getPrice: function(){
                let door_price = this.door_price;
                let measurment = this.measurment == 'in' ? 1540 : 1;
                let areas = this.wall_tabs.reduce(function(area, tab){
                    return area + tab.area(tab.current_shape_id, measurment);
                }, 0);
                let doors = this.wall_tabs.reduce(function(price, tab){
                    return price + tab.get_door_price(door_price);
                }, 0);
                let price = (((this.price_per_squear_methers * areas) + doors) / 100).toFixed(2);
                this.price = ((this.price_per_squear_methers * areas) + doors);
                return price;
            }
        },
        methods: {
            colorWasSelected: function(i){
                for(let color of this.avalible_colors){
                    color.isSelected = false;
                }
                this.avalible_colors[i].isSelected = true;
                this.selected_color = this.avalible_colors[i].title;
            },
            measurmentWasSelected: function(){
                this.measurment = event.target.value;
            },
            imageWasSelected: function(event){
                this.image = event.target.files[0];
                this.imageIsSelected = true;
            },
            addWallTab: function(){
                if(this.wall_tabs.length < 9){
                    let biggestId = this.wall_tabs.reduce(function(prev, current){
                        return (prev.id > current.id) ? prev : current;
                    }).id;
                    let newWallTab = get_wall_tab();
                    newWallTab.id = (biggestId + 1);
                    this.wall_tabs.push(newWallTab);
                    this.current_wall_tab_id = newWallTab.id;
                }
            },
            removeWallTab: function(tab_index){
                if(this.wall_tabs.length > 1){
                    this.wall_tabs.splice(tab_index, 1);
                    this.current_wall_tab_id = this.wall_tabs[(this.wall_tabs.length - 1)].id;
                }
            },
            tab_was_changed: function(tab_id, tab_index){
                this.current_wall_tab_id = tab_id;
            },
            changeToLeftTab: function(){
                let index = this.wall_tabs.findIndex(t => t.id == this.current_wall_tab_id);
                if(this.wall_tabs[(index - 1)]){
                    this.current_wall_tab_id = this.wall_tabs[(index - 1)].id;
                }
            },
            changeToRightTab: function(){
                let index = this.wall_tabs.findIndex(t => t.id == this.current_wall_tab_id);
                if(this.wall_tabs[(index + 1)]){
                    this.current_wall_tab_id = this.wall_tabs[(index + 1)].id;
                }
            },
            shapeWasSelected: function(tab_index, shape_index, shape_id){
                this.wall_tabs[tab_index].current_shape_id = shape_id;
                this.wall_tabs[tab_index].current_svg_name = this.wall_tabs[tab_index].available_shapes[shape_index].svg_name;
            },
            fillOrderWithWallsInfo: function(){
                let ordered_walls = [];
                this.wall_tabs.map(function(wall_tab, wall_tab_index){
                    ordered_walls[wall_tab_index] = {
                        shape_id: wall_tab.current_shape_id,
                        shape_dimensions: [],
                        door_included: wall_tab.door_included,
                        door_starts_from: wall_tab.door_starts_from,
                        additional_information: wall_tab.section_additional_information
                    };
                    wall_tab.available_shapes.map(function(shape){
                        if(shape.id === ordered_walls[wall_tab_index].shape_id){
                            shape.dimensions.map(function(dimension){
                                ordered_walls[wall_tab_index].shape_dimensions.push({
                                    letter: dimension.letter,
                                    value: dimension.value
                                });
                            });
                        }
                    });
                });
                return ordered_walls;
            },
            validateWalls: function(){
                this.wall_tabs.map(tab => {
                    tab.validate(this);
                });
            },
            resetMsg: function(){
                this.msg = 'valid';
            },
            showPaymentDetailsForm: function(){
                event.preventDefault();
                this.validateWalls();
                if(this.msg === 'valid'){
                    let myUrl = this.myUrl;
                    this.walls = this.fillOrderWithWallsInfo();

                    let stripe = StripeCheckout.configure({
                      key: 'pk_test_lVZX6Oqmev8YxYF9Ub1a4TNp00tlWJ7s94',
                      image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                      locale: 'auto',
                      token: function(token) {
                        // Use the token to create the charge with a server-side script.
                        // You can access the token ID with `token.id`
                        let data = {
                            order: {
                                selected_color: this.selected_color,
                                image: this.image,
                                measurment: this.measurment,
                                walls: this.walls
                            },
                            stripe: {
                                email: token.email,
                                source: token.id
                            }
                        };
                        axios.post(myUrl, data)
                          .then(function (response) {
                            if(response != 'error'){
                                console.log(response.data);
                            } else {
                                console.log('error');
                            }
                        })
                          .catch(function (error) {
                            console.log(error);
                        });
                      }
                    });

                    stripe.open({
                      name: 'windproofcurtains',
                      description: 'wall/s',
                      amount: this.price,
                      currency: 'gbp'
                    });
                }
            }
        }
    });
}
</script>