document.addEventListener('DOMContentLoaded', () => {
    const toggleVisibility = document.getElementById('toggle-visibility');
    const accountAmount = document.getElementById('account-amount');
    let isHidden = false;

    toggleVisibility.addEventListener('click', () => {
        if (isHidden) {
            accountAmount.style.visibility = 'visible';
        } else {
            accountAmount.style.visibility = 'hidden';
        }
        isHidden = !isHidden;
    });
});

function changeCurrency(currency) {
    // Define conversion rates with SGD as the base currency
    const conversionRates = {
        'SGD': 1,
        'USD': 0.75,
        'EUR': 0.65
        // Add more currencies and their rates as needed
    };

    // Get the current values in SGD
    const currentAmount = parseFloat(document.getElementById('account-amount').dataset.value);
    const currentMarketValue = parseFloat(document.getElementById('total-market-value').dataset.value);
    const currentTotalCash = parseFloat(document.getElementById('total-cash').dataset.value);

    // Convert the values to the selected currency
    const convertedAmount = (currentAmount * conversionRates[currency]).toFixed(2);
    const convertedMarketValue = (currentMarketValue * conversionRates[currency]).toFixed(2);
    const convertedTotalCash = (currentTotalCash * conversionRates[currency]).toFixed(2);

    // Update the displayed values
    document.getElementById('account-amount').innerText = `${currency} ${convertedAmount}`;
    document.getElementById('total-market-value').innerText = `Total Market Value: ${currency} ${convertedMarketValue}`;
    document.getElementById('total-cash').innerText = `Total Cash: ${currency} ${convertedTotalCash}`;

    // Update the currency label
    document.getElementById('currency-label').innerText = currency;
}
