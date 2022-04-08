<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');

if(defined('_INDEX_')) { // index에서만 실행
	include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
}


if(empty($member['mb_id'])){
	goto_url(G5_BBS_URL."/login.php");
}

$mb_id = $member['mb_id'];


/* 위 컨텐츠 */
/* DNB1 */
$sql = "select * from g5_order as ord left join g5_product as prd on ord.prd_id = prd.prd_id where prd.prd_type = 'DNB1' and ord_yn = 'Y' and ord_sc_yn = 'Y' and ord_del_yn = 'N' and mb_id = '{$mb_id}' order by ord_id desc limit 1";
$DNB1 = sql_fetch($sql);

$sql = "select count(*) as cnt from g5_payment where mb_id = '{$mb_id}' and ord_id = '{$DNB1['ord_id']}'";
$DNB1_CNT = sql_fetch($sql);

$sql = "select * from g5_wallet where mb_id = '{$mb_id}' and wlt_type = 'DNB1'";
$DNB1_WLT = sql_fetch($sql);

/* DNB2 */
$sql = "select * from g5_order as ord left join g5_product as prd on ord.prd_id = prd.prd_id where prd.prd_type = 'DNB2' and ord_yn = 'Y' and ord_sc_yn = 'Y' and ord_del_yn = 'N' and mb_id = '{$mb_id}' order by ord_id desc limit 1";

$DNB2 = sql_fetch($sql);

$sql = "select count(*) as cnt from g5_payment where mb_id = '{$mb_id}' and ord_id = '{$DNB2['ord_id']}'";
$DNB2_CNT = sql_fetch($sql);

$sql = "select * from g5_wallet where mb_id = '{$mb_id}' and wlt_type = 'DNB2'";
$DNB2_WLT = sql_fetch($sql);


//나의 누적 보상
$sql = "select * from g5_order as ord left join g5_product as prd on ord.prd_id = prd.prd_id where ord_yn = 'Y' and ord_sc_yn = 'Y' and ord_del_yn = 'N' and mb_id = '{$mb_id}' order by ord_id desc";
$rtn = sql_query($sql);

$DNB_PRICE = array();
$TODAY_PRICE = array();
$ndate = date("Y-m-d");
while($row = sql_fetch_array($rtn)){
	

	// 나의 누적보상
	$sql = "select * from g5_payment where ord_id = '{$row['ord_id']}' and pmt_del_yn = 'N'";
	$pay_rtn = sql_query($sql);

	while($pay_row = sql_fetch_array($pay_rtn)){

		$DNB_PRICE[$row['prd_type']] = $DNB_PRICE[$row['prd_type']] + $pay_row['pmt_price'];
		$DNB_PRICE['total_price'][$row['prd_type']] = $DNB_PRICE['total_price'][$row['prd_type']] + $pay_row['pmt_price'];
	}

	// 나의 누적보상
	$sql = "select * from g5_payment where ord_id = '{$row['ord_id']}' and pmt_del_yn = 'N' and LEFT(pmt_dttm, 10) = '{$ndate}'";
	$pay_rtn = sql_query($sql);

	while($pay_row = sql_fetch_array($pay_rtn)){

		$TODAY_PRICE[$row['prd_type']] = $TODAY_PRICE[$row['prd_type']] + $pay_row['pmt_price'];
		$TODAY_PRICE['total_price'][$row['prd_type']] = $TODAY_PRICE['total_price'][$row['prd_type']] + $pay_row['pmt_price'];
	}
}





?>


<!-- Begin Page Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">



    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="container-title">
            <span class="container-title-content">DNBplus (DNB)</span>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-6 order-lg-2 order-xl-1">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14 container-relative">
                        <div class="rico_float_left">
                            <img class="flaticon_img" src="<?php echo G5_IMG_URL?>/ic_package@3x.png">
                        </div>
                        <div class="rico_float_right rico_right">
                            <p class="rico_bold">나의 패키지
                                <!-- <span class="plus" onclick="showPopup('myPackage');">+</span> -->
                            </p>
                            <p class="rico_mb0 rico_font30"><?php echo number_format($DNB1['prd_price']); ?> DNB</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 order-lg-2 order-xl-1">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14 container-relative">
                        <div class="rico_float_left">
                            <img class="flaticon_img" src="<?php echo G5_IMG_URL?>/ic_daily@3x.png">
                        </div>
                        <div class="rico_float_right rico_right">
                            <p class="rico_bold">나의 수익률</p>
                            <p class="rico_mb0 rico_font30"><?php echo number_format($DNB1['prd_percent']); ?> %</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 order-lg-2 order-xl-1">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14 container-relative">
                        <div class="rico_float_left">
                            <img class="flaticon_img" src="<?php echo G5_IMG_URL?>/ic_d-day@3x.png">
                        </div>
                        <div class="rico_float_right rico_right">
                            <p class="rico_bold">패키지 D-day</p>
                            <div>
                                <span class="rico_mb0 rico_font30"><?php echo $DNB1_CNT['cnt']; ?></span>
                                <span
                                    style="font-weight: 300; font-size: 20px; align-items: center; margin-left: 4px; margin-right: 4px;">/</span>
                                <span class="rico_mb0 rico_font30"><?php echo $DNB1['prd_day_count']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 order-lg-2 order-xl-1">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14 container-relative">
                        <div class="rico_float_left">
                            <img class="flaticon_img" src="<?php echo G5_IMG_URL?>/ic_wallet@3x.png">
                        </div>
                        <div class="rico_float_right rico_right">
                            <p class="rico_bold">사용 가능 자산</p>
                            <p class="rico_mb0 rico_font30">DNB <?php echo number_format($DNB1_WLT['wlt_price']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-title">
            <span class="container-title-content">DNBplus+ (USDT)</span>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-6 order-lg-2 order-xl-1">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14 container-relative">
                        <div class="rico_float_left">
                            <img class="flaticon_img" src="<?php echo G5_IMG_URL?>/ic_package@3x.png">
                        </div>
                        <div class="rico_float_right rico_right">
                            <p class="rico_bold">나의 패키지
                                <!-- <span class="plus" onclick="showPopup('myPackage');">+</span> -->
                            </p>
                            <p class="rico_mb0 rico_font30"><?php echo number_format($DNB2['prd_price']); ?> USDT</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 order-lg-2 order-xl-1">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14 container-relative">
                        <div class="rico_float_left">
                            <img class="flaticon_img" src="<?php echo G5_IMG_URL?>/ic_daily@3x.png">
                        </div>
                        <div class="rico_float_right rico_right">
                            <p class="rico_bold">나의 수익률</p>
                            <p class="rico_mb0 rico_font30"><?php echo number_format($DNB2['prd_percent']); ?> %</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 order-lg-2 order-xl-1">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14 container-relative">
                        <div class="rico_float_left">
                            <img class="flaticon_img" src="<?php echo G5_IMG_URL?>/ic_d-day@3x.png">
                        </div>
                        <div class="rico_float_right rico_right">
                            <p class="rico_bold">패키지 D-day</p>
                            <div>
                                <span class="rico_mb0 rico_font30"><?php echo $DNB2_CNT['cnt']; ?></span>
                                <span
                                    style="font-weight: 300; font-size: 20px; align-items: center; margin-left: 4px; margin-right: 4px;">/</span>
                                <span class="rico_mb0 rico_font30"><?php echo $DNB2['prd_day_count']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 order-lg-2 order-xl-1">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14 container-relative">
                        <div class="rico_float_left">
                            <img class="flaticon_img" src="<?php echo G5_IMG_URL?>/ic_wallet@3x.png">
                        </div>
                        <div class="rico_float_right rico_right">
                            <p class="rico_bold">사용 가능 자산</p>
                            <p class="rico_mb0 rico_font30">USDT <?php echo $DNB2_WLT['wlt_price']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-title">
            <span class="container-title-content">나의 보상</span>
        </div>
        <div class="row">
            <div class="col-xl-6 col-lg-6 order-lg-1 order-xl-1" style="margin-bottom:20px;">
                <div class="kt-portlet kt-portlet--fit kt-portlet--head-noborder today-bonus">
                    <div style="display: grid; grid-template-rows: auto 1fr auto; grid-template-columns: 100%;">
                        <div class="today-bonus-title">
                            <h3 class="container-title-content">나의 누적 보상</h3>
                        </div>
                        <div>
                            <div class="kt-card">
                                <div class="today-card-bonus">
                                    <span class="bonus-label"><img class="ic_dash_bonus"
                                            src="<?php echo G5_IMG_URL?>/ic_asset_circle.svg" style="width: 29px;">누적
                                        보상액</span>
                                    <div class="bonus-value">
                                        <span class="dollar">
											<? echo $TODAY_PRICE['total_price']['DNB1']." DNB / ".$TODAY_PRICE['total_price']['DNB2']." USDT"; ?>
										</div>
                                </div>
                            </div>
                            <div class="today-bonus-graph">
                                <div class="kt-card" style="height:89px;">
                                    <span class="today-bonus-graph-title">누적 보상 비율</span>
                                    <div class="graph-bg">
                                        <div class="graph-bar" id="todayBonusDaily" style="width: 50%;"></div>
                                        <div class="graph-bar" id="todayBonusMatching" style="width: 100%;"></div>
                                    </div>
                                </div>
                                <ul class="today-bonus-graph-supplement">
                                    <li><i></i>DNBplus (DNB)</li>
                                    <li><i></i>DNBplus+ (USDT)</li>
                                </ul>
                            </div>
                        </div>
                        <div class="kt-line"></div>
                    </div>
                    <div>
                        <div class="today-bonus-title">
                            <h4>누적 보상 리스트</h4>
                        </div>
                        <div class="bonus-value-list">
                            <div class="bonus-value-list-row">
                                <img class="ic_dash_bonus" src="<?php echo G5_IMG_URL?>/ic_asset_daily_dashboard.svg">
                                <span>DNBplus (DNB)</span>
                                <span class="value">DNB <?php echo ($DNB_PRICE['DNB1']); ?></span>
                            </div>
                            <div class="bonus-value-list-row">
                                <img class="ic_dash_bonus"
                                    src="<?php echo G5_IMG_URL?>/ic_dash_today_daily.svg">
                                <span>DNBplus+ (USDT)</span>
                                <span class="value">USDT <?php echo ($DNB_PRICE['DNB2']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 order-lg-1 order-xl-1" style="margin-bottom:20px;">
                <div class="kt-portlet kt-portlet--fit kt-portlet--head-noborder"
                    style="display: grid; height: 100%; grid-template-rows: 323fr 289fr">
                    <div class="kt-portlet__head kt-portlet__space-x"
                        style="display: grid; grid-template-rows: auto 1fr auto 1px; grid-template-columns: 1fr;">
                        <div style="width: 100%; padding: 25px 0;">
                            <h3 class="container-title-content">
                                오늘의 보상 </h3>
                        </div>
                        <div
                            style="height:178px; box-shadow: 0 3px 20px 0 #f2f3f8; background-color: #f0f2f8; display: grid; padding: 24px; border-radius: 10px; grid-template-rows: auto 1fr auto; grid-template-columns: 100%;">
                            <div>
                                <div
                                    style="align-self: center; display: grid; grid-template-rows: 100%; grid-template-columns: auto 1fr;">
                                    <img class="flaticon_img" src="img/ic_dash_bonus.svg"
                                        style="width: 18px; height: auto;">
                                    <span
                                        style="color: #848c8e;font-size: 13px; font-weight: bold; align-content: center; display: grid; margin-left: 7px;">오늘의
                                        총 보상액</span>
                                </div>
                            </div>
                            <div style="margin: auto 0 15px auto;">
                                <div
                                    style="align-self: center; font-size: 34px; font-weight: 500;color: #645b5b; text-align: end;">
                                    <span>
                                        <? echo $TODAY_PRICE['total_price']['DNB1']." DNB / ".$TODAY_PRICE['total_price']['DNB2']." USDT"; ?>
									</span>
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #818181; text-align: end;">
                                    <span style="font-weight: 300; ">Last updated</span>
                                    <span style="font-weight: 500; ">&nbsp;<?php echo G5_TIME_YMD?></span>
                                </div>
                            </div>


                        </div>
                        
                        <div style="padding: 25px 0; font-size: 10px; color: #818181; text-align: center;">
                        </div>
                        <div class="kt-line"></div>
                    </div>
                    <div style="padding:0 20px;">
                        <div class="today-bonus-title">
                            <h4><?php echo G5_TIME_YMD?> 보상</h4>
                        </div>
                        <div class="bonus-value-list">
                            <div class="bonus-value-list-row">
                                <img class="ic_dash_bonus" src="<?php echo G5_IMG_URL?>/ic_asset_daily_dashboard.svg">
                                <span>DNBplus (DNB)</span>
                                <span class="value">DNB
                                    <? echo $TODAY_PRICE['DNB1']; ?></span>
                            </div>
                            <div class="bonus-value-list-row">
                                <img class="ic_dash_bonus" src="<?php echo G5_IMG_URL?>/ic_dash_today_daily.svg">
                                <span>DNBplus+ (USDT)</span>
                                <span class="value">USDT
                                    <? echo $TODAY_PRICE['DNB2']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-space-20"></div>
        </div>
    </div>


    <div class="popup-base" style="display: none;">
        <div class="popup-container" id="myPackage" style="display: none;">
            <div class="popup-contents">
                <div class="popup-top">
                    <h1>나의 패키지</h1>
                </div>
                <div class="popup-center">
                    <div class="popup-center-head" style="align-items: end; padding-bottom: 14px;">
                        <div style="opacity: 0.5">패키지</div>
                        <div style="opacity: 0.5">수량</div>
                    </div>

                    <div class="popup-center-body">
                        <?php
											while($row = sql_fetch_array($prd)) {

										?>
                        <div class="popup-center-body-row">
                            <div><?php echo $row['prd_name']; ?></div>
                            <div>0</div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="popup-bottom">
                    <div class="btn-list">
                        <a href="javascript:closePopup('myPackage');" class="close">닫기</a>
                        <a href="./product_pricing.php" class="more">더 구매하기</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //팝업 닫기
        function closePopup(popup_id) {
            // 회색 배경 닫기
            $(".popup-base").eq(0).css("display", "none");

            // 나의 패키지 팝업 닫기
            $("#" + popup_id).css("display", "none");
        }

        //팝업 열기
        function showPopup(popup_id) {
            // 회색 배경 열기
            $(".popup-base").eq(0).css("display", "block");

            // 나의 패키지 팝업 열기
            $("#" + popup_id).css("display", "grid");
        }
    </script>
    <!-- <script>
        $(function () {
            var list_point_sum = $.parseJSON('[]');
            var list_point_date = $.parseJSON('[]');

            var list_member_count = $.parseJSON('[]');
            var list_member_date = $.parseJSON('[]');

            // chart 3
            var dataMin = "0"; // 지급된 수당
            var dataMax = "0"; // 최초 수당

            // dataMin = 2100 - 2100;
            dataMin = (dataMax - dataMin);
            dataMax = dataMax - dataMin;

            dataMin = dataMin.toFixed(2);
            dataMax = dataMax.toFixed(2);

            var randomScalingFactor = function () {
                return Math.round(Math.random() * 100);
            };

            var config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [
                            dataMin, dataMax
                        ],
                        backgroundColor: [
                            KTApp.getStateColor('success'),
                            KTApp.getStateColor('warning')

                        ]
                    }],
                    labels: [
                        '수당 지급 완료',
                        '남은 수당'
                    ]
                },
                options: {
                    cutoutPercentage: 75,
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        position: 'top',
                        reverse: true
                    },
                    title: {
                        display: false,
                        text: 'Technology'
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    tooltips: {
                        enabled: true,
                        intersect: false,
                        mode: 'nearest',
                        bodySpacing: 5,
                        yPadding: 10,
                        xPadding: 10,
                        caretPadding: 0,
                        displayColors: false,
                        backgroundColor: KTApp.getStateColor('brand'),
                        titleFontColor: '#ffffff',
                        cornerRadius: 4,
                        footerSpacing: 0,
                        titleSpacing: 0
                    }
                }
            };
            var ctx = KTUtil.getByID('kt_chart_profit_share').getContext('2d');
            var myDoughnut = new Chart(ctx, config);

            // chart 4
            var chartContainer = KTUtil.getByID('kt_chart_daily_sales').getContext('2d');
            var gradient4 = chartContainer.createLinearGradient(0, 0, 0, 240);
            gradient4.addColorStop(0, Chart.helpers.color('#80c1ff').alpha(0.24).rgbString());
            gradient4.addColorStop(1, Chart.helpers.color('#ffffff').alpha(0.28).rgbString());
            var chartData = {
                labels: list_member_date,
                datasets: [{
                    //label: 'Dataset 1',
                    backgroundColor: gradient4,
                    borderColor: '#80c1ff',

                    pointBackgroundColor: Chart.helpers.color('#80c1ff').alpha(1)
                        .rgbString(),
                    pointBorderColor: Chart.helpers.color('#80c1ff').alpha(1)
                        .rgbString(),

                    data: list_member_count
                }]
            };

            var memberCountStr = (list_member_count);
            var memberCount = memberCountStr.map(function (i) {
                return parseInt(i, 10);
            });
            var maxCount = Math.max.apply(null, memberCount);
            console.log(maxCount);

            var chart = new Chart(chartContainer, {
                type: 'line',
                data: chartData,
                options: {
                    title: {
                        display: false,
                    },
                    tooltips: {
                        intersect: false,
                        mode: 'nearest',
                        xPadding: 10,
                        yPadding: 10,
                        caretPadding: 10
                    },
                    legend: {
                        display: false
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    barRadius: 4,
                    scales: {
                        // xAxes: [{
                        //     display: false,
                        //     gridLines: false,
                        //     stacked: true
                        // }],
                        yAxes: [{
                            display: true,
                            stacked: true,
                            ticks: {
                                suggestedMax: (maxCount % 10 + 1) * 10,
                                stepSize: Math.round(maxCount / 2)
                            }

                            // gridLines: false
                        }]
                    },
                    layout: {
                        padding: {
                            left: 0,
                            right: 0,
                            top: 0,
                            bottom: 0
                        }
                    }
                }
            });

            // 오늘 보너스 발생 그래프
            initTodayBonusGraph();
        });

        // update today -> cumulative
        function initTodayBonusGraph() {

            // 데일리
            let todayBonusDaily = Number('');

            // 매칭
            let todayBonusMatching = Number('');


            // 빈값의 경우 (1로 채움)
            if (todayBonusDaily == 0 && todayBonusMatching == 0) {
                todayBonusDaily = 1;
                todayBonusMatching = 1;
            }

            let totalTodayBonus = todayBonusDaily + todayBonusMatching;
            let daily_percent = todayBonusDaily / totalTodayBonus * 100;
            let matching_percent = todayBonusMatching / totalTodayBonus * 100;

            let total_per = 0;

            // 데일리
            total_per = total_per + daily_percent;
            $("#todayBonusDaily").css("width", total_per + "%");

            // 매칭
            total_per = total_per + matching_percent;
            $("#todayBonusMatching").css("width", total_per + "%");



        } -->

    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script>
        var w = 225,
            h = 225;
        var graphData = [ < ? php echo "$all_my_staking"; ? > , < ? php echo "$min_money"; ? > ];
        var colorData = ["#0abb87", "#ffb822"];
        var pie = d3.pie();
        var arc = d3.arc().innerRadius(75).outerRadius(110);

        var svg = d3.select(".one-graph")
            .append("svg")
            .attr("width", w)
            .attr("height", h)
            .attr("id", "graphWrap");

        var g = svg.selectAll(".pie")
            .data(pie(graphData))
            .enter()
            .append("g")
            .attr("class", "pie")
            .attr("stroke", "white")
            .attr("transform", "translate(" + w / 2 + "," + h / 2 + ")");

        g.append("path")
            .style("fill", function (d, i) {
                return colorData[i];
            })
            .transition()
            .duration(500)
            .delay(function (d, i) {
                return i * 500;
            })
            .attrTween("d", function (d, i) {
                var interpolate = d3.interpolate({
                    startAngle: d.startAngle,
                    endAngle: d.startAngle
                }, {
                    startAngle: d.startAngle,
                    endAngle: d.endAngle
                });
                return function (t) {
                    return arc(interpolate(t));
                }
            });


        svg.append("text")
            .attr("class", "total")
            .attr("transform", "translate(" + (w / 2 - 35) + ", " + (h / 2 + 5) + ")")
            .text("보상한도:" + < ? php echo "$limit_money"; ? > );
    </script>


    <!-- /.container-fluid -->

    <?php
include_once(G5_THEME_PATH.'/tail.php');
?>