export function useFormatNumber() {

    function formatNumber(number) {
        if (number === 0) return "0";
        if (!number) return "";

        let parts = number.toString().split(".");

        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        if (parts[1] && parseInt(parts[1]) > 0) {
            parts[1] = parts[1].substring(0, 2); // Limit to two decimals
            return parts.join(","); // Join with a comma as the decimal separator
        }

        return parts[0]; // If decimals are 0, only show the integer part
    }

    return { formatNumber };
}