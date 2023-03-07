const postData = data => {
    fetch('./create_form.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    console.log(JSON.stringify(data))
}

export default postData