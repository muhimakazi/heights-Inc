/**
 * Participant MODULE
 * Page: Dashboard
 */
import * as Init from "../../core/Init.js";
import * as General from "../../public/general.js";
import * as HTTP from "../../core/http.js";
import * as Security from "../../core/Security.js";

// Getting the authentication token

// Admin Dashboard
let participantsData = [];

function registrationFigures(data, token) {
  let url = Init.getApiV1URL() + "/participants/figures";

  HTTP.doGet(url, data, token).then((response) => {
    if (response.status === "200") {
      // Request completed successfully
      let figuresData = response.data;
      wirteRegistrationFigures(figuresData.general);
      writeCategoryFigures(figuresData.categories);
      writePendingCategoryFigures(figuresData.categoriesPending);
      writeApprovedCategoryFigures(figuresData.categoriesApproved);
      //   console.log(figuresData.general);
    } else {
      console.log(
        "An error occured while fetching dashboard data. Reference: " +
          response.message
      );
    }
  });
}

function wirteRegistrationFigures(data) {
  document.getElementById("total_registrations").innerHTML =
    data.totalRegistrations;
  document.getElementById("pending_registrations").innerHTML =
    data.pendingRegistrations;
  document.getElementById("approved_registrations").innerHTML =
    data.approvedRegistrations;
  document.getElementById("rejected_registrations").innerHTML =
    data.rejectedRegistrations;
}

function writeCategoryFigures(data) {
  // VIP Figures
  document.getElementById("total_vip").innerHTML = data.vip;
  document.getElementById("vip_percentage").innerHTML =
    data.vipPercentage + "%";

  // Industry Figures
  document.getElementById("total_industry").innerHTML = data.industry;
  document.getElementById("industry_percentage").innerHTML =
    data.industryPercentage + "%";

  // Startup Figures
  document.getElementById("total_startup").innerHTML = data.startup;
  document.getElementById("startup_percentage").innerHTML =
    data.startupPercentage + "%";

  // Government Figures
  document.getElementById("total_government").innerHTML = data.government;
  document.getElementById("government_percentage").innerHTML =
    data.governmentPercentage + "%";

  // Media Figures
  document.getElementById("total_media").innerHTML = data.media;
  document.getElementById("media_percentage").innerHTML =
    data.mediaPercentage + "%";

  // CMPD Figures
  document.getElementById("total_cmpd").innerHTML = data.cmpd;
  document.getElementById("cmpd_percentage").innerHTML =
    data.cmpdPercentage + "%";

  // Speaker Figures
  document.getElementById("total_speaker").innerHTML = data.speaker;
  document.getElementById("speaker_percentage").innerHTML =
    data.speakerPercentage + "%";
}

// PENDING REGISTRATIONS
function writePendingCategoryFigures(data) {
  // VIP Figures
  document.getElementById("pending_total_vip").innerHTML = data.vip;
  document.getElementById("pending_vip_percentage").innerHTML =
    data.vipPercentage + "%";

  // Industry Figures
  document.getElementById("pending_total_industry").innerHTML = data.industry;
  document.getElementById("pending_industry_percentage").innerHTML =
    data.industryPercentage + "%";

  // Startup Figures
  document.getElementById("pending_total_startup").innerHTML = data.startup;
  document.getElementById("pending_startup_percentage").innerHTML =
    data.startupPercentage + "%";

  // Government Figures
  document.getElementById("pending_total_government").innerHTML =
    data.government;
  document.getElementById("pending_government_percentage").innerHTML =
    data.governmentPercentage + "%";

  // Media Figures
  document.getElementById("pending_total_media").innerHTML = data.media;
  document.getElementById("pending_media_percentage").innerHTML =
    data.mediaPercentage + "%";

  // CMPD Figures
  document.getElementById("pending_total_cmpd").innerHTML = data.cmpd;
  document.getElementById("pending_cmpd_percentage").innerHTML =
    data.cmpdPercentage + "%";

  // Speaker Figures
  document.getElementById("pending_total_speaker").innerHTML = data.speaker;
  document.getElementById("pending_speaker_percentage").innerHTML =
    data.speakerPercentage + "%";
}

// APPROVED REGISTRATIONS
function writeApprovedCategoryFigures(data) {
  // VIP Figures
  document.getElementById("approved_total_vip").innerHTML = data.vip;
  document.getElementById("approved_vip_percentage").innerHTML =
    data.vipPercentage + "%";

  // Industry Figures
  document.getElementById("approved_total_industry").innerHTML = data.industry;
  document.getElementById("approved_industry_percentage").innerHTML =
    data.industryPercentage + "%";

  // Startup Figures
  document.getElementById("approved_total_startup").innerHTML = data.startup;
  document.getElementById("approved_startup_percentage").innerHTML =
    data.startupPercentage + "%";

  // Government Figures
  document.getElementById("approved_total_government").innerHTML =
    data.government;
  document.getElementById("approved_government_percentage").innerHTML =
    data.governmentPercentage + "%";

  // Media Figures
  document.getElementById("approved_total_media").innerHTML = data.media;
  document.getElementById("approved_media_percentage").innerHTML =
    data.mediaPercentage + "%";

  // CMPD Figures
  document.getElementById("approved_total_cmpd").innerHTML = data.cmpd;
  document.getElementById("approved_cmpd_percentage").innerHTML =
    data.cmpdPercentage + "%";

  // Speaker Figures
  document.getElementById("approved_total_speaker").innerHTML = data.speaker;
  document.getElementById("approved_speaker_percentage").innerHTML =
    data.speakerPercentage + "%";
}

// Getting Payment Data
function paymentFigures(data, token) {
  let url = Init.getApiV1URL() + "/participants/paymentdata";

  HTTP.doGet(url, data, token).then((response) => {
    if (response.status === "200") {
      // Request completed successfully
      let paymentFiguresData = response.data;
      wirtePaymentFigures(paymentFiguresData);
      //   console.log(figuresData.general);
    } else {
      console.log(
        "An error occured while fetching dashboard data. Reference: " +
          response.message
      );
    }
  });
}

function wirtePaymentFigures(data) {
  let completedAmount =
    data.completedAmount == undefined ? "0" : data.completedAmount;
  let totalCompleted =
    data.totalCompleted == undefined ? "0" : data.totalCompleted;
  let completedPercentage =
    data.completedPercentage == undefined ? "0" : data.completedPercentage;
  let pendingAmount =
    data.pendingAmount == undefined ? "0" : data.pendingAmount;
  let totalPending = data.totalPending == undefined ? "0" : data.totalPending;
  let pendingPercentage =
    data.pendingPercentage == undefined ? "0" : data.pendingPercentage;
  let refundedAmount =
    data.refundedAmount == undefined ? "0" : data.refundedAmount;
  let totalRefunded =
    data.totalRefunded == undefined ? "0" : data.totalRefunded;
  let refundedPercentage =
    data.refundedPercentage == undefined ? "0" : data.refundedPercentage;

  // Completed Payment figures
  document.getElementById("completed_amount").innerHTML = "$" + completedAmount;
  document.getElementById("total_completed").innerHTML = totalCompleted;
  document.getElementById("completed_percentage").innerHTML =
    completedPercentage + "%";
  $(".completed_percentage").css("width", data.completedPercentage + "%");

  // Pending payment figures
  document.getElementById("pending_amount").innerHTML = "$" + pendingAmount;
  document.getElementById("total_pending").innerHTML = totalPending;
  document.getElementById("pending_percentage").innerHTML =
    pendingPercentage + "%";
  $(".pending_percentage").css("width", pendingPercentage + "%");

  // Refunded payment figures
  document.getElementById("refunded_amount").innerHTML = "$" + refundedAmount;
  document.getElementById("total_refunded").innerHTML = totalRefunded;
  document.getElementById("refunded_percentage").innerHTML =
    refundedPercentage + "%";
  $(".refunded_percentage").css("width", refundedPercentage + "%");

  // Pie Chart
  var doughnutData = [
    {
      value: data.totalIndustry,
      color: "#a3e1d4",
      highlight: "#1ab394",
      label: "Industry",
    },
    {
      value: data.totalStartup,
      color: "#dedede",
      highlight: "#979797",
      label: "Startup",
    },
  ];

  var doughnutOptions = {
    segmentShowStroke: true,
    segmentStrokeColor: "#fff",
    segmentStrokeWidth: 2,
    percentageInnerCutout: 45, // This is 0 for Pie charts
    animationSteps: 100,
    animationEasing: "easeOutBounce",
    animateRotate: true,
    animateScale: false,
    responsive: true,
  };

  var ctx = document.getElementById("pass-comparison-chart").getContext("2d");
  var myNewChart = new Chart(ctx).Doughnut(doughnutData, doughnutOptions);
}

/*-------------------------------
 * EVENT LISTENERS
 * -------------------------------
 */

document.addEventListener("DOMContentLoaded", function () {
  // your code goes here
  let authToken = "";

  window.setTimeout(function () {
    let active_event = document.getElementById("active-event").value.trim();
    if (active_event !== "") {
      // Building the Bearer Authentication token
      authToken = Security.BEARER_AUTH(active_event);

      // Get Registration Figures
      registrationFigures("", authToken);

      paymentFigures("", authToken);
    }
  }, 1000);
});
