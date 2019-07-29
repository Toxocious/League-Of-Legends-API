/**
 * The initial function to fetch a Summoner and their respective data.
 */
function FetchSummoner()
{
	let Summoner = $('input[name="summoner"]').val();
	let Region = $('select[name="region"]').val();

	// If a Summoner name hasn't been entered, tell the client.
	if ( !Summoner || Summoner == '' )
	{
		DisplayNotice('Please enter a valid Summoner name.');
		return false;
	}

	$('.main-form').fadeOut(500);
	$('.css-loader').show(500).css('display', 'block');

	$.ajax({
		type: 'get',
		url: 'assets/php/ajax/summoner.php',
		data: { Summoner: Summoner, Region: Region },
		success: function(data)
		{
			$('.css-loader').fadeOut(500, function()
			{
				$('.content').html(data);
			});
		},
		error: function(data)
		{
			$('.css-loader').fadeOut(500, function()
			{
				$('.content').html(data);
			});
		}
	});
}

/**
 * Display a custom notice to the client.
 */
function DisplayNotice(Text)
{
	// Set the timeout delay.
	let Timeout = 5000;

	// In the circumstance that multiple notices are being generated, append a random integer to the notice class.
	let RandInt = Math.floor(Math.random() * 10000) + 1;
	$('.container').prepend("<div class='notice' id='notice_" + RandInt + "'>" + Text + "</div>");
	$('div#notice_' + RandInt).animate({ top: '+=46' });

	// After 3s, remove the notice.
	setTimeout(function()
	{
		$('#notice_' + RandInt).fadeOut(500);
	}, Timeout);
}