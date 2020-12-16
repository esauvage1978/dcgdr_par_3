changeTemplate();

$('#indicator_indicatortype').change(function () {
    changeTemplate();
});

function changeTemplate() {

    let divgoal = $('#div_indicator_goal');
    let divinitialValue = $('#div_indicator_value');
    let goal = $('#indicator_goal');
    let initialValue = $('#indicator_value');

    let valType = getVal($('#indicator_indicatortype'));

    var urlGoal = '/ajax/indicator/showgoal/' + valType;
    var urlInitialValue = '/ajax/indicator/showinitialvalue/' + valType;

    divgoal.fadeOut();
    divinitialValue.fadeOut();
    setVal(goal, '');
    setVal(initialValue, '');

    axios.get(urlGoal).then(function (response) {
        if (response.data) {
            divgoal.fadeIn(300);
            if (getVal(goal) == "") {
                setVal(goal, '100');
            }
        } else {
            divgoal.fadeOut();
        }
    }).catch(function (error) {
        console.log(error);
    });

    axios.get(urlInitialValue).then(function (response) {
        if (response.data) {
            divinitialValue.fadeIn(300);
            if (getVal(initialValue) == "") {
                setVal(initialValue, '0');
            }
        } else {
            console.log(response.data)
            divinitialValue.fadeOut();
        }
    }).catch(function (error) {
        console.log(error);
    });
}
