jQuery(function(t){t("tr.csvmode").fadeOut(),t('input[name="wysija[import][type]"]').click(function(){t(".form-valid").validationEngine("hide"),t("tr.csvmode").fadeOut(),"copy"==this.value?t("tr.csvmode.copy").fadeIn():t("tr.csvmode.upload").fadeIn();}),t("#copy-paste").click(),t("#csvtext").attr("defaultVal","email;name\njohndoe@mailpoet.com; John Doe\njanedoe@mailpoet.com; Jane Doe\njohnny_smith@mailpoet.com; Johnny Smith"),t("#csvtext").blur(function(){""==t(this).val()&&(t(this).val(t(this).attr("defaultVal")),t(this).hasClass("empty")||t(this).addClass("empty"));}),t("#csvtext").focus(function(){t(this).val()==t(this).attr("defaultVal")&&(t(this).val(""),t(this).removeClass("empty"));}),t(".form-valid").submit(function(){return t("#csvtext").val()==t("#csvtext").attr("defaultVal")&&t("#csvtext").val(""),!0;}),t(document).ready(function(){t("#csvtext").blur();});});