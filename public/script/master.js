function goLoading(msg) {
    $.blockUI({ 
        baseZ: 30000,
        centerX: true, 
        centerY: true, 
        css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff',
            theme: true,
            textAlign: 'center', 
        },
        message: msg,
    }); 
}