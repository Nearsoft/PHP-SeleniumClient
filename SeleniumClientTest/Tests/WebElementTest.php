<?php

require_once __DIR__ . '/AbstractTest.php';

use SeleniumClient\By;
use SeleniumClient\WebElement;

class WebElementTest extends AbstractTest
{
    public function testGetCoordinatesInViewShouldGetLocationOnScreenOnceScrolledIntoView()
	{
		$element = $this->_driver->findElement(By::id("txt1"));
		$coordinates = $element->getLocationOnScreenOnceScrolledIntoView();
		
		$this->assertTrue(is_numeric($coordinates["x"]) );
		$this->assertTrue(is_numeric($coordinates["y"]) );
	}
	
	
	public function testGetCoordinatesShouldGetCoordinates()
	{
		$element = $this->_driver->findElement(By::id("txt1"));
		$coordinates = $element->getCoordinates();
		
		$this->assertTrue(is_numeric($coordinates["x"]) );
		$this->assertTrue(is_numeric($coordinates["y"]) );
	}
	
	public function testIsDisplayedShouldDetermineIfDisplayed()
	{
		$button1 = $this->_driver->findElement(By::id("btnNoAction"));
		$this->assertEquals( true, $button1->isDisplayed());	
		$this->_driver->executeScript("document.getElementById('btnNoAction').style.display = 'none';");
		$this->assertEquals( false, $button1->isDisplayed());
	}
	
	public function testGetAttributeShouldGetData()
	{
		$chk = $this->_driver->findElement(By::id("chk3"));
		$this->assertEquals( "chk3",strtolower($chk->getAttribute("name")));
	}
	
	public function testIsEnabledShouldDetermineIfEnabled()
	{
		$button1 = $this->_driver->findElement(By::id("btnNoAction"));
		$this->assertEquals( true, $button1->isEnabled());	
	
		$this->_driver->executeScript("document.getElementById('btnNoAction').disabled = true;");
		
		$this->assertEquals( false, $button1->isEnabled());
	}
	
	
	public function testIsSelectedShouldDetermineIfSelected()
	{
		$selectBox = $this->_driver->findElement(By::id("sel1"));
	
		$selectBoxOption = $selectBox->findElement(By::xPath("/html/body/table/tbody/tr/td/fieldset/form/p[3]/select/option[1]"));
		
		$this->assertEquals( false, $selectBoxOption->isSelected());
		
		$selectBoxOption->click();
		
		$this->assertEquals( true, $selectBoxOption->isSelected());
	}

    public function testClassMethodsAffectElementClassName()
    {
        $element = $this->_driver->findElement(By::id("sel1"));
        $this->assertEmpty($element->getClassName());
        $element->setClassName("select x-small");
        $this->assertEquals("select x-small", $element->getClassName());
        $this->assertContains('class="select x-small"', $element->getOuterHTML());
        // test method chaining
        $element->addClass("foo")->addClass("bar");
        $this->assertEquals("select x-small foo bar", $element->getClassName());
        // we shouldn't be able to add a duplicate class
        $element->addClass("foo");
        $this->assertEquals("select x-small foo bar", $element->getClassName());
        $element->removeClass("foo");
        $this->assertEquals("select x-small bar", $element->getClassName());
    }
	
	public function testClearShouldSetValueEmpty()
	{
		$webElement = $this->_driver->findElement(By::id("txt1"));
	
		$webElement->sendKeys("test text");
	
		$webElement->clear();
	
		$this->assertEquals("", trim($webElement->getAttribute("value")));
	}

    public function testGetInnerHTMLShouldGetInnerHTML()
    {
        $selectBox = $this->_driver->findElement(By::id("sel1"));
        $text = $selectBox->getInnerHTML();
        $this->assertContains('<option', $text);
        $this->assertStringEndsNotWith('</select>', $text);
    }

    public function testGetOuterHTMLShouldGetOuterHTML()
    {
        $selectBox = $this->_driver->findElement(By::id("sel1"));
        $text = $selectBox->getOuterHTML();
        $this->assertContains('<option', $text);
        $this->assertStringEndsWith('</select>', $text);
    }

	public function testGetTagNameShouldGetTagName()
	{
		$webElement = $this->_driver->findElement(By::id("txt1"));
		$this->assertEquals("input",strtolower($webElement->getTagName()));
	}
	
	
	public function testDescribeShouldGetElementId()
	{
		
		$webElement = $this->_driver->findElement(By::id("txt1"));
		
		$webElementDescription = $webElement->describe();
		
		$this->assertEquals( $webElement->getElementId(), trim($webElementDescription["id"]));	
	}

    public function testFindElementByJsSelectorShouldGetChildElement()
    {
        $selectBox = $this->_driver->findElement(By::id("sel1"));
        $option = $selectBox->findElement(By::jsSelector('option[selected="selected"]', 'document.querySelector'));
        $this->assertEquals('Orange', $option->getText());
    }
	
	public function testFindElementShouldGetFoundElementText()
	{
	
		$selectBox = $this->_driver->findElement(By::id("sel1"));
	
		$selectBoxOption = $selectBox->findElement(By::xPath("/html/body/table/tbody/tr/td/fieldset/form/p[3]/select/option[2]"));
	
		$this->assertTrue($selectBoxOption instanceof  WebElement);
		
		$this->assertEquals( "Red", $selectBoxOption->getText() );
	}
	
	public function testFindElementsShouldGetOneOfFoundElementsText()
	{
	
		$selectBox = $this->_driver->findElement(By::id("sel1"));
	
		$selectBoxOptions = $selectBox->findElements(By::tagName("option"));

		foreach($selectBoxOptions as $selectBoxOption)
		{
			
			$this->assertTrue($selectBoxOption instanceof  WebElement);
			if($selectBoxOption->getAttribute("value") == "4")
			{
				
				$selectBoxOption->click();
			}
		}

		foreach($selectBoxOptions as $selectBoxOption)
		{
			
			if($selectBoxOption->getAttribute("selected") == "true")
			{
				$this->assertEquals("Black", $selectBoxOption->getText());
			}		
		}
	}

	public function testClickShouldSubmitForm()
	{	
		$button = $this->_driver->findElement(By::id("btnSubmit"));
		
		$button->click();
		
		$this->assertTrue(strstr($this->_driver->getCurrentPageUrl(), "formReceptor") >= 0);
	}
	
	public function testSubmitShouldSubmitForm()
	{
		$button = $this->_driver->findElement(By::id("btnSubmit"));
	
		$button->submit();
	
		$this->assertTrue(strstr($this->_driver->getCurrentPageUrl(), "formReceptor") >= 0);
	}
	
	public function testTextShouldGetText()
	{
		$label = $this->_driver->findElement(By::xPath("/html/body/table/tbody/tr/td[2]/fieldset/p"));
		$this->assertEquals("Simple paragraph", $label->getText());
	}
	
	public function testSendKeysShouldRetreiveText()
	{
		$textarea1 = $this->_driver->findElement(By::id("txtArea1"));
		$textarea1->sendKeys("TEST");
		$this->assertEquals("TEST", $textarea1->getAttribute("value"));
	}

	public function testSendKeysShouldRetrieveHebrewText()
	{
		$textarea1 = $this->_driver->findElement(By::id("txtArea1"));
		$textarea1->sendKeys("יאיר 34557");
		$this->assertEquals("יאיר 34557", $textarea1->getAttribute("value"));
	}
	//TODO TEST WITH INVALID URL, INVALID PORT INVALID BROWSERNAME	
}