function getSearchParamsIsPage() {
    const link = location.href
    const params = new URL(link).searchParams
    return params
}