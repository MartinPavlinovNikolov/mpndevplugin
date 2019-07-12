<script type="text/javascript">
window.onload = function(){

    jQuery('body').append(`
        <div id="mpndevmodal" style='
            display: none;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100vh;
            background-color:rgba(0,0,0,.6);
        '>
            <div style='
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                width: 300px;
                background-color: #f5f5f7;
                border: 2px solid white;
                border-radius: 6px;
                box-shadow: 0 12px 30px 0 rgba(0,0,0,.5),inset 0 1px 0 0 hsla(0,0%,100%,.65);
                box-sizing: border-box;
            '>

                <span id="mpndevclose" style='
                    box-sizing: border-box;
                    position: absolute;
                    width: 25px;
                    height: 25px;
                    right: 6px;
                    top: 6px;
                    border: 1px solid #ccc;
                    border-radius: 50%;
                    margin: 0 auto;
                    padding: 0px 8px;
                    color: #f51151;
                '>x</span>

                <h3 style='
                    margin: 30px 15px;
                    font-size: 17px;
                    font-weight: 700;
                    color: #000;
                    text-shadow: 0 1px 0 #fff;
                    text-align: center;
                    box-sizing: border-box;
                '>Please, fill your<br>contacts information</h3>

                <input style='
                    box-sizing: border-box;
                    border-radius: 4px;
                    width: 80%
                ' id="mpndevname" type="text" required placeholder="name">

                <input style='
                    box-sizing: border-box;
                    border-radius: 4px;
                    width: 80%
                ' id="mpndevemail" type="email" required placeholder="email">

                <input style='
                    box-sizing: border-box;
                    border-radius: 4px;
                    width: 80%
                ' id="mpndevaddress" type="text" required placeholder="address">

                <input style='
                    box-sizing: border-box;
                    border-radius: 4px;
                    width: 80%
                ' id="mpndevphone" type="text" required placeholder="phone">

                <a style='
                    box-sizing: border-box;
                    margin-bottom: 20px;
                ' class="button button-primary normal alternative-1 order-now" id="mpndevsubmit">order with stripe</a>

                <span style='
                    box-sizing: border-box;
                    color: red;
                ' id="mpndeverror"></span>

            </div>
        </div>
    `);

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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (A - offset) || door.start < 5){
                            that.msg = this.errorMessages[3];
                        }
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B >= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return ((this.dimensions[0].value * this.dimensions[3].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '1.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (A - offset) || door.start < 5){
                            that.msg = this.errorMessages[3];
                        }
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B <= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return ((this.dimensions[0].value * this.dimensions[1].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '2.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let C = this.dimensions[0].value | 0;
                    let D = this.dimensions[1].value | 0;
                    let d = this.dimensions[2].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (C - offset) || door.start < 5){
                            that.msg = this.errorMessages[2];
                        }
                    }
                    if(d >= D){
                        that.msg = this.errorMessages[1];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return (((this.dimensions[1].value + (this.dimensions[2].value * 2)) * this.dimensions[0].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '3.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'D',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let B = this.dimensions[0].value | 0;
                    let b = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (C - offset) || door.start < 5){
                            that.msg = this.errorMessages[2];
                        }
                    }
                    if(b >= B){
                        that.msg = this.errorMessages[1];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return (((this.dimensions[0].value + (this.dimensions[1].value * 2)) * this.dimensions[2].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '4.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'b',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (A - offset) || door.start < 5){
                            that.msg = this.errorMessages[3];
                        }
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B <= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return ((this.dimensions[2].value * this.dimensions[3].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '5.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (A - offset) || door.start < 5){
                            that.msg = this.errorMessages[3];
                        }
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B >= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return ((this.dimensions[2].value * this.dimensions[1].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '6.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (A - offset) || door.start < 5){
                            that.msg = this.errorMessages[3];
                        }
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B >= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return ((this.dimensions[0].value * this.dimensions[3].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '7.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let D = this.dimensions[3].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (A - offset) || door.start < 5){
                            that.msg = this.errorMessages[3];
                        }
                    }
                    if(A >= C){
                        that.msg = this.errorMessages[1];
                    }
                    if(B <= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return ((this.dimensions[0].value * this.dimensions[1].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '8.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let b = this.dimensions[2].value | 0;
                    let C = this.dimensions[3].value | 0;
                    let D = this.dimensions[4].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (A - offset) || door.start < 5){
                            that.msg = this.errorMessages[3];
                        }
                    }
                    if(B <= D){
                        that.msg = this.errorMessages[1];
                    }
                    if(b >= B){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return (((this.dimensions[1].value + (this.dimensions[2].value * 2)) * this.dimensions[3].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '9.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'b',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let A = this.dimensions[0].value | 0;
                    let B = this.dimensions[1].value | 0;
                    let C = this.dimensions[2].value | 0;
                    let d = this.dimensions[3].value | 0;
                    let D = this.dimensions[4].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (A - offset) || door.start < 5){
                            that.msg = this.errorMessages[3];
                        }
                    }
                    if(B >= D){
                        that.msg = this.errorMessages[1];
                    }
                    if(d >= D){
                        that.msg = this.errorMessages[2];
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return (((this.dimensions[4].value + (this.dimensions[3].value * 2)) * this.dimensions[2].value)  / (measurment == 'in' ? 0.15500 : 1)) / 10000;;
                },
                svg_name: '10.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'A',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'B',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'd',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                    let door_width = door.measurment == 'in' ? (door.width / 2.54) : door.width;
                    let offset = door.measurment == 'in' ? (5 / 2.54) : 5;
                    let C = this.dimensions[0].value | 0;
                    if(door.included == true){
                        if((door_width + (door.start | 0)) > (C - offset) || door.start < 5){
                            that.msg = this.errorMessages[1];
                        }
                    }
                    this.dimensions.map(function(dimension){
                        dimension.validate(that, this);
                    }, this);
                },
                area: function(measurment){
                    return ((this.dimensions[0].value * this.dimensions[1].value) / (measurment == 'in' ? 0.15500 : 1)) / 10000;
                },
                svg_name: '11.svg',
                dimensions: [
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
                        },
                        letter: 'C',
                        value: 0
                    },
                    {
                        validate: function(that, parrent){
                            that.msg = (Number.isInteger(this.value | 0) && this.value > 0) ? that.msg : parrent.errorMessages[0];
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
                if(this.door_included == true){
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
                            height: that.door_height,
                            measurment: that.measurment
                        });
                    }
                });
            },
            area: function(shapeId, measurment){
                return (this.available_shapes.filter(function(s){
                    return s.id == shapeId
                })[0].area(measurment));
            },
            get_door_price: function(door_price){
                return this.door_included ? door_price : 0;
            },
            door_included: false,
            door_starts_from: 0,
            available_shapes: get_available_shapes(),
            current_svg_name: '1.svg',
            section_additional_information: '',
            current_shape_id: 1
        };
    }

    let mpndevpluginvue = new Vue({
        el: '#setup_order_box',
        data: {
            mpndevmodal: jQuery,
            username: '',
            email: '',
            address: '',
            phone: '',
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
                let measurment = this.measurment;
                let areas = this.wall_tabs.reduce(function(area, tab){
                    return area + tab.area(tab.current_shape_id, measurment);
                }, 0);
                let doors = this.wall_tabs.reduce(function(price, tab){
                    return price + tab.get_door_price(door_price);
                }, 0);
                let price = (((this.price_per_squear_methers * areas) + doors) / 100).toFixed(2);
                this.price = ((this.price_per_squear_methers * areas) + doors) | 0;
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
            showModalCredentials: function(){
                this.validateWalls();
                if(this.msg === 'valid'){
                    this.mpndevmodal('#mpndevmodal').css('display', 'flex');
                }
            },
            modalWasSubmited: function(e){
                e.preventDefault();
                let errMsg = '';
                if(this.username.length < 1){
                    errMsg = 'User name cannot be empty!';
                }
                if(this.email.length < 6){
                    errMsg = 'Invalid email!';
                }
                if(this.address.length < 2){
                    errMsg = 'Ops, do not forget the address!';
                }
                if(this.phone.match(/^[0-9\s\+]*$/) == null){
                    errMsg = 'Telephone number can contain numbers only!';
                }
                if(this.phone.length < 6){
                    errMsg = 'Telephone number cannot be less then six numbers!';
                }
                if(errMsg.length > 0){
                    this.mpndevmodal('#mpndeverror').text(errMsg);
                }else {
                    this.mpndevmodal('#mpndevmodal').css('display', 'none');
                    this.showPaymentDetailsForm();
                }
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
                if(this.image == null){
                    this.msg = 'Please, upload image of the place where you will use windcurtains';
                }
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
                let that = this;
                if(this.msg === 'valid'){
                    let myUrl = this.myUrl;
                    this.walls = this.fillOrderWithWallsInfo();

                    let stripe = StripeCheckout.configure({
                      key: '<?= MPNDEV_STRIPE_PUBLIC_KEY ?>',
                      image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                      locale: 'auto',
                      token: function(token) {
                        // Use the token to create the charge with a server-side script.
                        // You can access the token ID with `token.id`
                        let formData = new FormData();
                        formData.append('image', that.image);
                        let data = {
                            order: {
                                selected_color: that.selected_color,
                                measurment: that.measurment,
                                walls: that.walls,
                                price: that.price,
                                username: that.username,
                                email: that.email,
                                address: that.address,
                                phone: that.phone
                            },
                            stripe: {
                                email: token.email,
                                source: token.id
                            }
                        };
                        
                        formData.append('data', JSON.stringify(data));
                        axios({
                            method: 'post',
                            url: myUrl,
                            data: formData,
                            config: {headers : {'Content-type': 'multipart/form-data'}}
                        })
                        .then(function (response) {
                            if(response != 'error'){
                                that.showPositiveFeedback();
                            } else {
                                that.showFailFeedback();
                            }
                        })
                        .catch(function (error) {
                            that.showFailFeedback();
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
            },
            showPositiveFeedback: function(){
                jQuery('body').append(`
                    <div id="mpndevmodalsuccess" style='
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 100%;
                        height: 100vh;
                        background-color:rgba(0,0,0,.6);
                    ' onclick="return jQuery('#mpndevmodalsuccess').remove();">
                        <div style='
                            position: relative;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-direction: column;
                            width: 300px;
                            background-color: #f5f5f7;
                            border: 2px solid white;
                            border-radius: 6px;
                            box-shadow: 0 12px 30px 0 rgba(0,0,0,.5),inset 0 1px 0 0 hsla(0,0%,100%,.65);
                            box-sizing: border-box;
                        '>

                            <h3 style='
                                margin: 30px 15px;
                                font-size: 17px;
                                font-weight: 700;
                                color: #000;
                                text-shadow: 0 1px 0 #fff;
                                text-align: center;
                                box-sizing: border-box;
                            '>Thanks for your purchase</h3>

                            <a style='
                                box-sizing: border-box;
                                margin-bottom: 20px;
                            ' class="button button-primary normal alternative-1 order-now">close</a>

                        </div>
                    </div>
                `);
            },
            showFailFeedback: function(){
                jQuery('body').append(`
                    <div id="mpndevmodalfail" style='
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 100%;
                        height: 100vh;
                        background-color:rgba(0,0,0,.6);
                    ' onclick="return jQuery('#mpndevmodalfail').remove();">
                        <div style='
                            position: relative;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-direction: column;
                            width: 300px;
                            background-color: #f5f5f7;
                            border: 2px solid white;
                            border-radius: 6px;
                            box-shadow: 0 12px 30px 0 rgba(0,0,0,.5),inset 0 1px 0 0 hsla(0,0%,100%,.65);
                            box-sizing: border-box;
                        '>

                            <h3 style='
                                margin: 30px 15px;
                                font-size: 17px;
                                font-weight: 700;
                                color: #000;
                                text-shadow: 0 1px 0 #fff;
                                text-align: center;
                                box-sizing: border-box;
                            '>Something went wrong.<br>Please, try again.</h3>

                            <a style='
                                box-sizing: border-box;
                                margin-bottom: 20px;
                            ' class="button normal alternative-1 order-now">OK</a>

                        </div>
                    </div>
                `);
            }
        }
    });

    jQuery('#mpndevname').on("change paste keyup", function(e){
        mpndevpluginvue.mpndevmodal('#mpndeverror').text('');
        mpndevpluginvue.username = e.target.value;
    });
    jQuery('#mpndevemail').on("change paste keyup", function(e){
        mpndevpluginvue.mpndevmodal('#mpndeverror').text('');
        mpndevpluginvue.email = e.target.value;
    });
    jQuery('#mpndevaddress').on("change paste keyup", function(e){
        mpndevpluginvue.mpndevmodal('#mpndeverror').text('');
        mpndevpluginvue.address = e.target.value;
    });
    jQuery('#mpndevphone').on("change paste keyup", function(e){
        mpndevpluginvue.mpndevmodal('#mpndeverror').text('');
        mpndevpluginvue.phone = e.target.value;
    });
    jQuery('#mpndevsubmit').on("click", function(e){
        mpndevpluginvue.modalWasSubmited(e);
    });
    jQuery('#mpndevmodal').on("click", function(e){
        if(jQuery(e.target).attr('id') == 'mpndevmodal'){
            jQuery(this).css('display', 'none');
        }
    });
    jQuery('#mpndevclose').on("click", function(){
        jQuery('#mpndevmodal').css('display', 'none');
    });

}
</script>