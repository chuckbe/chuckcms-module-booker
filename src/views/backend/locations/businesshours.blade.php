<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.js"></script>
<script>
! function(a) {
    a.fn.businessHours = function(b) {
        var c = {
                preInit: function() {},
                postInit: function() {},
                checkedColorClass: "WorkingDayState",
                uncheckedColorClass: "RestDayState",
                colorBoxValContainerClass: "colorBoxContainer",
                weekdays: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                operationTime: [{}, {}, {}, {}, {}, {
                    isActive: !1
                }, {
                    isActive: !1
                }],
                defaultOperationTimeFrom: "9:00",
                defaultOperationTimeTill: "18:00",
                defaultActive: !0,
                containerTmpl: '<div class="clean"/>',
                dayTmpl: `
                    <div class="dayContainer">
                        <div data-original-title="" class="colorBox">
                            <input type="checkbox" class="invisible operationState"/>
                        </div>
                        <div class="weekday"></div>
                        <div class="operationDayTimeContainer">
                            <div class="operationTime">
                                <input type="text" name="startTime" class="mini-time operationTimeFrom" value=""/>
                            </div>
                            <div class="operationTime">
                                <input type="text" name="endTime" class="mini-time operationTimeTill" value=""/>
                            </div>
                        </div>
                    </div>`
            },
            d = a(this),
            e = {
                getValueOrDefault: function(a, b) {
                    return "undefined" === jQuery.type(a) || null == a ? b : a
                },
                init: function(b) {
                    return this.options = a.extend(c, b), d.html(""), "function" == typeof this.options.preInit && this.options.preInit(), this.initView(this.options), "function" == typeof this.options.postInit && this.options.postInit(), {
                        serialize: function() {
                            var b = [];
                            return d.find(".operationState").each(function(c, d) {
                                var e = a(d).prop("checked"),
                                    f = a(d).parents(".dayContainer");
                                b.push({
                                    isActive: e,
                                    timeFrom: e ? f.find("[name='startTime']").val() : null,
                                    timeTill: e ? f.find("[name='endTime']").val() : null
                                })
                            }), b
                        }
                    }
                },
                initView: function(b) {
                    for (var c = [b.checkedColorClass, b.uncheckedColorClass], e = d.append(a(b.containerTmpl)), f = this, g = 0; g < b.weekdays.length; g++) e.append(b.dayTmpl);
                    a.each(b.weekdays, function(a, c) {
                        var e = b.operationTime[a],
                            g = d.find(".dayContainer").eq(a);
                        g.find(".weekday").html(c);
                        var h = f.getValueOrDefault(e.isActive, b.defaultActive);
                        g.find(".operationState").prop("checked", h);
                        var i = f.getValueOrDefault(e.timeFrom, b.defaultOperationTimeFrom);
                        g.find('[name="startTime"]').val(i);
                        var j = f.getValueOrDefault(e.timeTill, b.defaultOperationTimeTill);
                        g.find('[name="endTime"]').val(j)
                    }), d.find(".operationState").change(function() {
                        var d = a(this),
                            e = b.checkedColorClass,
                            f = !1;
                        d.prop("checked") || (e = b.uncheckedColorClass, f = !0), d.parents(".colorBox").removeClass(c.join(" ")).addClass(e), d.parents(".dayContainer").find(".operationTime").toggle(!f)
                    }).trigger("change"), d.find(".colorBox").on("click", function() {
                        var b = a(this).find(".operationState");
                        b.prop("checked", !b.prop("checked")).trigger("change")
                    })
                }
            };
        return e.init(b)
    }
}(jQuery);

$("#businessHoursContainer").businessHours({
    postInit:function(){
        $('.operationTimeFrom, .operationTimeTill').timepicker({
            'timeFormat': 'H:i',
            'step': 15
        });
    },
    dayTmpl:'<div class="dayContainer" style="width: 80px;">' +
        '<div data-original-title="" class="colorBox"><input type="checkbox" class="invisible operationState"></div>' +
        '<div class="weekday"></div>' +
        '<div class="operationDayTimeContainer">' +
        '<div class="operationTime input-group"><span class="input-group-addon"><i class="fa fa-sun-o"></i></span><input type="text" name="startTime" class="mini-time form-control operationTimeFrom" value=""></div>' +
        '<div class="operationTime input-group"><span class="input-group-addon"><i class="fa fa-moon-o"></i></span><input type="text" name="endTime" class="mini-time form-control operationTimeTill" value=""></div>' +
        '</div></div>'
});

</script>