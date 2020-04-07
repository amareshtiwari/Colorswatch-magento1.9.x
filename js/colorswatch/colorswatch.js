/*
 * Codesbug Colorswatch
 * Developed By : Codesbug technologies pvt. Lmt.
 *
 * This js Work on category single product page
 * It require jquery Lib to run(uploaded by module also no need to upload explicitly)
 */

jQuery(document).ready(function () {
  /*
   * hideSelect() function hides all select tag which have no Image
   */
  hideSelects();
  /*
   * setCorrectLabels() Sets the initial options for select tag and div tags
   */
  setCorrectLabels();

  /*
   * change event runs on when any select option would change and arrange all sub select tag acordingly
   */
  jQuery(".input-box select").change(function () {
    var selectLength = jQuery(".input-box select").length;
    var currentSelectIndex = jQuery(".input-box select").index(jQuery(this));
    for (var i = currentSelectIndex; i < selectLength - 1; i++) {
      var nextSelect = jQuery(".input-box select").eq(i + 1);
      nextSelect.empty();
      nextSelect.append("<option value>Choose an Option...</option>");
      nextSelect.prop("disabled", "disabled");
    }
    setOptionLabels(jQuery(this));
    setCorrectLabels();
  });

  /*
   * This colors event runs when user click on custom image box and it
   * select the value to hidden select tag
   */
  jQuery("body").on("click", ".colors", function () {
    jQuery(".colors").each(function () {
      jQuery(this).css("box-shadow", "0px 0px 0px white");
    });
    var selectTag = jQuery(this).parents(".input-box").find("select");
    selectTag.val(jQuery(this).prop("id"));
    selectTag.change();
    var div = jQuery(this).parents();
    selectTag
      .parent()
      .children(".colors")
      .css("box-shadow", "1px 3px 4px blue");
  });
});
/*
 * setOptionLabels() call by select change event and arrange the all further
 * select option acording to current select value
 */
function setOptionLabels(mthis) {
  var products = [];
  var optVal = mthis.val();
  var attrId = parseInt(mthis.prop("id").replace(/[^\d.]/g, ""));
  var data = JSON.parse(getAllData());
  var options = data.attributes[attrId].options;
  options.forEach(function (cObj) {
    if (cObj.id == optVal) {
      cObj.products.forEach(function (cPro) {
        var selectIndex = jQuery("select").index(mthis);
        if (selectIndex > 0) {
          var prevSelect = jQuery("select").eq(selectIndex - 1);
          var prevSelectAttrId = parseInt(
            prevSelect.prop("id").replace(/[^\d.]/g, "")
          );
          if (!isNaN(prevSelectAttrId)) {
            var prevSelectOptions = data.attributes[prevSelectAttrId].options;
            prevSelectOptions.forEach(function (subObj) {
              if (subObj.id == prevSelect.val()) {
                if (subObj.products.indexOf(cPro) != -1) {
                  products.push(cPro);
                }
              }
            });
          }
        } else {
          products.push(cPro);
        }
      });
    }
  });
  var selectIndex = jQuery(".input-box select").index(mthis);
  var nextSelect = jQuery(".input-box select").eq(selectIndex + 1);
  if (nextSelect.length > 0) {
    var nextSelectAttrId = parseInt(
      nextSelect.prop("id").replace(/[^\d.]/g, "")
    );
    var nextSelectOptions = data.attributes[nextSelectAttrId].options;
    nextSelect.empty();
    nextSelect.append("<option value>Choose an Option...</option>");
    nextSelect.removeAttr("disabled");
    nextSelectOptions.forEach(function (cObj) {
      cObj.products.forEach(function (cPro) {
        if (products.indexOf(cPro) != -1) {
          if (jQuery("option[value=" + cObj.id + "]").length <= 0) {
            nextSelect.append(
              '<option value="' + cObj.id + '">' + cObj.label + "</option>"
            );
          }
        }
      });
    });
  }
}

/*
 * setCorrectLabels() sets the all selects and images,
 * it checks if select is hidden then it would show boxes otherwise it shows select tag
 */

function setCorrectLabels() {
  var data = JSON.parse(getAllData());
  var lastPrice = data.basePrice;
  jQuery("select").each(function () {
    var attrId = parseInt(
      jQuery(this)
        .prop("id")
        .replace(/[^\d.]/g, "")
    );

    if (jQuery(this).val() != "" && jQuery(this).val() != data.chooseText) {
      var optionPrice =
        data.attributes[attrId].options[jQuery(this)[0].selectedIndex - 1]
          .price;
      lastPrice = parseFloat(lastPrice) + parseFloat(optionPrice);
    }
    var code = data.attributes[attrId].code;
    if (!jQuery(this).is(":visible")) {
      var cSelect = jQuery(this);
      var options = data.attributes[attrId].options;
      jQuery("#" + attrId).remove();
      cSelect.parent().append('<div id="' + attrId + '"></div>');
      options.forEach(function (cObj) {
        if (jQuery("option[value=" + cObj.id + "]").length > 0) {
          if (cSelect.val() == cObj.id) {
            jQuery("#" + attrId).append(
              '<div title="' +
                cObj.label +
                '" ><img style="outline: 4px solid #FDBA3E;" id="' +
                cObj.id +
                '" class="colors" src="' +
                getBaseDir() +
                "colorswatch/" +
                attrId +
                "/" +
                cObj.id +
                '/img" alt="' +
                cObj.label +
                '"/></div>'
            );
          } else {
            jQuery("#" + attrId).append(
              '<div title="' +
                cObj.label +
                '" ><img id="' +
                cObj.id +
                '" class="colors" src="' +
                getBaseDir() +
                "colorswatch/" +
                attrId +
                "/" +
                cObj.id +
                '/img" alt="' +
                cObj.label +
                '"/></div>'
            );
          }
        } else {
          jQuery("#" + attrId).append(
            '<div><img class="invalidOptions" id="' +
              cObj.id +
              '"src="' +
              getBaseDir() +
              "colorswatch/" +
              attrId +
              "/" +
              cObj.id +
              '/img" alt="' +
              cObj.label +
              '"/></div>'
          );
        }
      });
      jQuery("#" + attrId).append("<br><br><br>");
    }
  });
  var currency = jQuery(".regular-price").children("span").text();
  jQuery(
    jQuery(".regular-price")
      .children("span")
      .text(currency[0] + lastPrice)
  );
}

/*
 * hideSelects() checks if product has image then it would hide the hide the select tag
 */
function hideSelects() {
  jQuery("select").each(function () {
    var attrId = parseInt(
      jQuery(this)
        .prop("id")
        .replace(/[^\d.]/g, "")
    );
    if (hasImage(attrId)) {
      jQuery(this).hide();
    }
  });
}

/*
 * hasImage() call by hideSelects() and use ajax send request to colorswatch/index/isdir
 * cntroller then controller checks if images are exist or not if it exist then it will return 1 otherwise 0-
 */
function hasImage(attId) {
  var yes;
  var url = getBaseUrl() + "colorswatch/index/isdir/";
  jQuery.ajax(url, {
    async: false,
    method: "post",
    data: {
      dir: attId,
    },
    success: function (data) {
      yes = JSON.parse(data).yes;
    },
  });
  return yes;
}
